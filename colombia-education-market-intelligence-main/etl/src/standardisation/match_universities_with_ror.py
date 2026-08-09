from pathlib import Path
from datetime import datetime, timezone
import json
import re
import unicodedata

import pandas as pd
from rapidfuzz import process, fuzz


PROJECT_ROOT = Path(__file__).resolve().parents[3]

PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REFERENCE_DIR = PROJECT_ROOT / "data" / "reference"
REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = PROCESSED_DIR / "colfuturo_selected_university_standardised.csv"
ROR_REFERENCE_FILE = REFERENCE_DIR / "universities_reference.csv"

OUTPUT_FILE = PROCESSED_DIR / "colfuturo_selected_ror_matched.csv"
MATCH_REPORT_FILE = REPORTS_DIR / "ror_university_match_report.json"
MATCH_CANDIDATES_FILE = REPORTS_DIR / "ror_university_match_candidates.csv"

FUZZY_SCORE_THRESHOLD = 90
FUZZY_CANDIDATE_LIMIT = 3


def normalise_text(value: object) -> str | None:
    if value is None or pd.isna(value):
        return None

    text = str(value).strip()

    if not text:
        return None

    text = unicodedata.normalize("NFKD", text)
    text = "".join(char for char in text if not unicodedata.combining(char))
    text = text.lower()
    text = re.sub(r"[^\w\s]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()

    return text


def load_inputs() -> tuple[pd.DataFrame, pd.DataFrame]:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run standardise_universities.py first."
        )

    if not ROR_REFERENCE_FILE.exists():
        raise FileNotFoundError(
            f"ROR reference file not found: {ROR_REFERENCE_FILE}. "
            "Run build_ror_university_reference.py first."
        )

    colfuturo = pd.read_csv(INPUT_FILE)
    ror_reference = pd.read_csv(ROR_REFERENCE_FILE)

    return colfuturo, ror_reference


def prepare_ror_reference(ror_reference: pd.DataFrame) -> pd.DataFrame:
    reference = ror_reference.copy()

    reference["normalised_alias_name"] = reference["normalised_alias_name"].apply(normalise_text)
    reference["country_code"] = reference["country_code"].astype("string")
    reference["country_code"] = reference["country_code"].str.upper()

    reference = reference.dropna(
        subset=[
            "normalised_alias_name",
            "country_code",
            "ror_id",
            "canonical_university_name",
        ]
    )

    # Prefer canonical names, then acronyms/aliases/labels if duplicates exist.
    alias_priority = {
        "canonical": 1,
        "acronym": 2,
        "alias": 3,
        "label": 4,
        "other": 5,
    }

    reference["alias_priority"] = reference["alias_type"].map(alias_priority).fillna(99)

    reference = (
        reference
        .sort_values(["country_code", "normalised_alias_name", "alias_priority"])
        .drop_duplicates(subset=["country_code", "normalised_alias_name"], keep="first")
        .reset_index(drop=True)
    )

    return reference


def build_exact_lookup(reference: pd.DataFrame) -> dict[tuple[str, str], dict]:
    lookup = {}

    for _, row in reference.iterrows():
        key = (row["country_code"], row["normalised_alias_name"])

        lookup[key] = {
            "ror_id": row["ror_id"],
            "ror_canonical_name": row["canonical_university_name"],
            "ror_alias_name": row["alias_name"],
            "ror_alias_type": row["alias_type"],
            "ror_country_code": row["country_code"],
            "ror_country_name": row["country_name"],
            "ror_city": row["city"],
            "match_method": "exact_country_alias",
            "match_score": 100,
            "match_status": "auto_accepted",
        }

    return lookup


def exact_match_university(
    university_name: object,
    country_code: object,
    exact_lookup: dict[tuple[str, str], dict],
) -> dict:
    normalised_name = normalise_text(university_name)

    if normalised_name is None or pd.isna(country_code):
        return empty_match("missing_source")

    country_code_text = str(country_code).upper().strip()
    key = (country_code_text, normalised_name)

    match = exact_lookup.get(key)

    if match:
        return match

    return empty_match("unmatched")


def empty_match(status: str) -> dict:
    return {
        "ror_id": None,
        "ror_canonical_name": None,
        "ror_alias_name": None,
        "ror_alias_type": None,
        "ror_country_code": None,
        "ror_country_name": None,
        "ror_city": None,
        "match_method": None,
        "match_score": None,
        "match_status": status,
    }


def apply_postgraduate_exact_matches(
    df: pd.DataFrame,
    exact_lookup: dict[tuple[str, str], dict],
) -> pd.DataFrame:
    output = df.copy()

    matches = output.apply(
        lambda row: exact_match_university(
            row.get("postgraduate_university_canonical"),
            row.get("destination_country_iso2"),
            exact_lookup,
        ),
        axis=1,
    )

    output["postgraduate_ror_id"] = matches.apply(lambda item: item["ror_id"])
    output["postgraduate_ror_canonical_name"] = matches.apply(lambda item: item["ror_canonical_name"])
    output["postgraduate_ror_alias_name"] = matches.apply(lambda item: item["ror_alias_name"])
    output["postgraduate_ror_alias_type"] = matches.apply(lambda item: item["ror_alias_type"])
    output["postgraduate_ror_country_code"] = matches.apply(lambda item: item["ror_country_code"])
    output["postgraduate_ror_country_name"] = matches.apply(lambda item: item["ror_country_name"])
    output["postgraduate_ror_city"] = matches.apply(lambda item: item["ror_city"])
    output["postgraduate_ror_match_method"] = matches.apply(lambda item: item["match_method"])
    output["postgraduate_ror_match_score"] = matches.apply(lambda item: item["match_score"])
    output["postgraduate_ror_match_status"] = matches.apply(lambda item: item["match_status"])

    return output


def build_fuzzy_candidates_for_unmatched(
    matched_df: pd.DataFrame,
    reference: pd.DataFrame,
) -> pd.DataFrame:
    unmatched = (
        matched_df.loc[
            matched_df["postgraduate_ror_match_status"] == "unmatched",
            [
                "postgraduate_university_name",
                "postgraduate_university_canonical",
                "destination_country_standardised",
                "destination_country_iso2",
            ],
        ]
        .drop_duplicates()
        .dropna(subset=["postgraduate_university_canonical", "destination_country_iso2"])
        .reset_index(drop=True)
    )

    candidate_rows = []

    for _, row in unmatched.iterrows():
        source_name = row["postgraduate_university_canonical"]
        source_name_normalised = normalise_text(source_name)
        country_code = str(row["destination_country_iso2"]).upper().strip()

        if source_name_normalised is None:
            continue

        country_reference = reference[reference["country_code"] == country_code].copy()

        if country_reference.empty:
            continue

        choices = country_reference["normalised_alias_name"].dropna().unique().tolist()

        fuzzy_matches = process.extract(
            source_name_normalised,
            choices,
            scorer=fuzz.WRatio,
            limit=FUZZY_CANDIDATE_LIMIT,
        )

        for candidate_normalised_name, score, _ in fuzzy_matches:
            if score < FUZZY_SCORE_THRESHOLD:
                continue

            candidate_reference = country_reference[
                country_reference["normalised_alias_name"] == candidate_normalised_name
            ].head(1)

            if candidate_reference.empty:
                continue

            candidate = candidate_reference.iloc[0]

            candidate_rows.append(
                {
                    "source_university_name": row["postgraduate_university_name"],
                    "source_canonical_name": row["postgraduate_university_canonical"],
                    "source_country_name": row["destination_country_standardised"],
                    "source_country_iso2": country_code,
                    "candidate_ror_id": candidate["ror_id"],
                    "candidate_ror_canonical_name": candidate["canonical_university_name"],
                    "candidate_ror_alias_name": candidate["alias_name"],
                    "candidate_ror_alias_type": candidate["alias_type"],
                    "candidate_country_name": candidate["country_name"],
                    "candidate_country_code": candidate["country_code"],
                    "candidate_city": candidate["city"],
                    "candidate_score": round(float(score), 2),
                    "candidate_status": "needs_review",
                }
            )

    candidates = pd.DataFrame(candidate_rows)

    if candidates.empty:
        return candidates

    candidates = (
        candidates
        .sort_values(
            ["candidate_score", "source_country_name", "source_canonical_name"],
            ascending=[False, True, True],
        )
        .reset_index(drop=True)
    )

    return candidates


def build_report(matched_df: pd.DataFrame, candidates: pd.DataFrame) -> dict:
    total_rows = int(len(matched_df))
    auto_accepted_rows = int((matched_df["postgraduate_ror_match_status"] == "auto_accepted").sum())
    unmatched_rows = int((matched_df["postgraduate_ror_match_status"] == "unmatched").sum())
    missing_source_rows = int((matched_df["postgraduate_ror_match_status"] == "missing_source").sum())

    unique_source_universities = int(
        matched_df["postgraduate_university_canonical"].nunique(dropna=True)
    )

    unique_matched_ror_ids = int(
        matched_df["postgraduate_ror_id"].nunique(dropna=True)
    )

    unique_unmatched_universities = int(
        matched_df.loc[
            matched_df["postgraduate_ror_match_status"] == "unmatched",
            "postgraduate_university_canonical",
        ].nunique(dropna=True)
    )

    candidate_count = int(len(candidates))

    return {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "ror_reference_file": str(ROR_REFERENCE_FILE),
        "output_file": str(OUTPUT_FILE),
        "match_candidates_file": str(MATCH_CANDIDATES_FILE),
        "total_rows": total_rows,
        "auto_accepted_rows": auto_accepted_rows,
        "unmatched_rows": unmatched_rows,
        "missing_source_rows": missing_source_rows,
        "auto_accepted_row_rate": round(auto_accepted_rows / total_rows, 4) if total_rows else 0,
        "unique_source_postgraduate_universities": unique_source_universities,
        "unique_matched_ror_ids": unique_matched_ror_ids,
        "unique_unmatched_postgraduate_universities": unique_unmatched_universities,
        "fuzzy_candidate_rows": candidate_count,
        "fuzzy_score_threshold": FUZZY_SCORE_THRESHOLD,
        "match_scope": "postgraduate destination universities only",
        "note": (
            "Exact normalised country-constrained ROR matches are auto-accepted. "
            "Fuzzy matches are generated as review candidates only and are not auto-applied."
        ),
    }


def save_json(data: dict, output_path: Path) -> None:
    with output_path.open("w", encoding="utf-8") as file:
        json.dump(data, file, indent=2, ensure_ascii=False)


def main() -> None:
    PROCESSED_DIR.mkdir(parents=True, exist_ok=True)
    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    colfuturo, ror_reference = load_inputs()

    reference = prepare_ror_reference(ror_reference)
    exact_lookup = build_exact_lookup(reference)

    matched_df = apply_postgraduate_exact_matches(colfuturo, exact_lookup)
    candidates = build_fuzzy_candidates_for_unmatched(matched_df, reference)

    matched_df.to_csv(OUTPUT_FILE, index=False, encoding="utf-8-sig")
    candidates.to_csv(MATCH_CANDIDATES_FILE, index=False, encoding="utf-8-sig")

    report = build_report(matched_df, candidates)
    save_json(report, MATCH_REPORT_FILE)

    print("ROR university matching summary")
    print("-------------------------------")
    print(f"Total rows: {report['total_rows']}")
    print(f"Auto-accepted exact match rows: {report['auto_accepted_rows']}")
    print(f"Unmatched rows: {report['unmatched_rows']}")
    print(f"Missing source rows: {report['missing_source_rows']}")
    print(f"Auto-accepted row rate: {report['auto_accepted_row_rate']}")
    print(f"Unique source postgraduate universities: {report['unique_source_postgraduate_universities']}")
    print(f"Unique matched ROR IDs: {report['unique_matched_ror_ids']}")
    print(f"Unique unmatched postgraduate universities: {report['unique_unmatched_postgraduate_universities']}")
    print(f"Fuzzy candidate rows: {report['fuzzy_candidate_rows']}")
    print(f"Output file: {OUTPUT_FILE}")
    print(f"Candidates file: {MATCH_CANDIDATES_FILE}")
    print(f"Report file: {MATCH_REPORT_FILE}")


if __name__ == "__main__":
    main()