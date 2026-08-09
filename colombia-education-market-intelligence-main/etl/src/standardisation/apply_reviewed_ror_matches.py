from pathlib import Path
from datetime import datetime, timezone
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = PROCESSED_DIR / "colfuturo_selected_ror_matched.csv"
REVIEW_FILE = REPORTS_DIR / "ror_high_confidence_review.csv"

OUTPUT_FILE = PROCESSED_DIR / "colfuturo_selected_ror_reviewed.csv"
REPORT_FILE = REPORTS_DIR / "ror_reviewed_match_application_report.json"


def load_inputs() -> tuple[pd.DataFrame, pd.DataFrame]:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run match_universities_with_ror.py first."
        )

    if not REVIEW_FILE.exists():
        raise FileNotFoundError(
            f"Review file not found: {REVIEW_FILE}. Run export_high_confidence_ror_review.py first."
        )

    matched_df = pd.read_csv(INPUT_FILE)
    review_df = pd.read_csv(REVIEW_FILE)

    return matched_df, review_df


def build_accepted_review_lookup(review_df: pd.DataFrame) -> dict[tuple[str, str], dict]:
    accepted = review_df[
        review_df["review_decision"].fillna("").str.lower().str.strip() == "accept"
    ].copy()

    lookup = {}

    for _, row in accepted.iterrows():
        key = (
            str(row["source_canonical_name"]).strip(),
            str(row["source_country_iso2"]).strip().upper(),
        )

        lookup[key] = {
            "ror_id": row["candidate_ror_id"],
            "ror_canonical_name": row["candidate_ror_canonical_name"],
            "ror_alias_name": row["candidate_ror_alias_name"],
            "ror_alias_type": row["candidate_ror_alias_type"],
            "ror_country_code": row["candidate_country_code"],
            "ror_country_name": row["candidate_country_name"],
            "ror_city": row["candidate_city"],
            "match_method": "reviewed_high_confidence_fuzzy",
            "match_score": row["candidate_score"],
            "match_status": "review_accepted",
        }

    return lookup


def apply_reviewed_matches(
    matched_df: pd.DataFrame,
    accepted_lookup: dict[tuple[str, str], dict],
) -> tuple[pd.DataFrame, int]:
    output = matched_df.copy()

    applied_count = 0

    for index, row in output.iterrows():
        if row.get("postgraduate_ror_match_status") != "unmatched":
            continue

        key = (
            str(row.get("postgraduate_university_canonical")).strip(),
            str(row.get("destination_country_iso2")).strip().upper(),
        )

        reviewed_match = accepted_lookup.get(key)

        if reviewed_match is None:
            continue

        output.at[index, "postgraduate_ror_id"] = reviewed_match["ror_id"]
        output.at[index, "postgraduate_ror_canonical_name"] = reviewed_match["ror_canonical_name"]
        output.at[index, "postgraduate_ror_alias_name"] = reviewed_match["ror_alias_name"]
        output.at[index, "postgraduate_ror_alias_type"] = reviewed_match["ror_alias_type"]
        output.at[index, "postgraduate_ror_country_code"] = reviewed_match["ror_country_code"]
        output.at[index, "postgraduate_ror_country_name"] = reviewed_match["ror_country_name"]
        output.at[index, "postgraduate_ror_city"] = reviewed_match["ror_city"]
        output.at[index, "postgraduate_ror_match_method"] = reviewed_match["match_method"]
        output.at[index, "postgraduate_ror_match_score"] = reviewed_match["match_score"]
        output.at[index, "postgraduate_ror_match_status"] = reviewed_match["match_status"]

        applied_count += 1

    return output, applied_count


def build_report(
    before_df: pd.DataFrame,
    after_df: pd.DataFrame,
    review_df: pd.DataFrame,
    applied_rows: int,
) -> dict:
    accepted_review_rows = int(
        (
            review_df["review_decision"].fillna("").str.lower().str.strip()
            == "accept"
        ).sum()
    )

    before_auto_accepted = int(
        (before_df["postgraduate_ror_match_status"] == "auto_accepted").sum()
    )
    before_unmatched = int(
        (before_df["postgraduate_ror_match_status"] == "unmatched").sum()
    )

    after_auto_accepted = int(
        (after_df["postgraduate_ror_match_status"] == "auto_accepted").sum()
    )
    after_review_accepted = int(
        (after_df["postgraduate_ror_match_status"] == "review_accepted").sum()
    )
    after_unmatched = int(
        (after_df["postgraduate_ror_match_status"] == "unmatched").sum()
    )

    total_rows = int(len(after_df))
    matched_rows_after = after_auto_accepted + after_review_accepted

    return {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "review_file": str(REVIEW_FILE),
        "output_file": str(OUTPUT_FILE),
        "total_rows": total_rows,
        "accepted_review_rows": accepted_review_rows,
        "reviewed_matches_applied_to_rows": int(applied_rows),
        "before_auto_accepted_rows": before_auto_accepted,
        "before_unmatched_rows": before_unmatched,
        "after_auto_accepted_rows": after_auto_accepted,
        "after_review_accepted_rows": after_review_accepted,
        "after_unmatched_rows": after_unmatched,
        "matched_rows_after_review": matched_rows_after,
        "matched_row_rate_after_review": round(matched_rows_after / total_rows, 4)
        if total_rows
        else 0,
        "unique_ror_ids_after_review": int(after_df["postgraduate_ror_id"].nunique(dropna=True)),
    }


def save_report(report: dict, output_path: Path) -> None:
    with output_path.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)


def main() -> None:
    PROCESSED_DIR.mkdir(parents=True, exist_ok=True)
    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    matched_df, review_df = load_inputs()

    accepted_lookup = build_accepted_review_lookup(review_df)
    reviewed_df, applied_rows = apply_reviewed_matches(matched_df, accepted_lookup)

    reviewed_df.to_csv(OUTPUT_FILE, index=False, encoding="utf-8-sig")

    report = build_report(
        before_df=matched_df,
        after_df=reviewed_df,
        review_df=review_df,
        applied_rows=applied_rows,
    )

    save_report(report, REPORT_FILE)

    print("Reviewed ROR match application summary")
    print("--------------------------------------")
    print(f"Accepted review rows: {report['accepted_review_rows']}")
    print(f"Reviewed matches applied to dataset rows: {report['reviewed_matches_applied_to_rows']}")
    print(f"Before unmatched rows: {report['before_unmatched_rows']}")
    print(f"After unmatched rows: {report['after_unmatched_rows']}")
    print(f"Matched row rate after review: {report['matched_row_rate_after_review']}")
    print(f"Unique ROR IDs after review: {report['unique_ror_ids_after_review']}")
    print(f"Output file: {OUTPUT_FILE}")
    print(f"Report file: {REPORT_FILE}")


if __name__ == "__main__":
    main()