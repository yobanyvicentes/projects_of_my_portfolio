from pathlib import Path
from datetime import datetime, timezone
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = PROCESSED_DIR / "colfuturo_selected_university_standardised.csv"

OUTPUT_CSV = REPORTS_DIR / "university_review_priority.csv"
OUTPUT_JSON = REPORTS_DIR / "university_review_priority_report.json"


def build_postgraduate_priority(df: pd.DataFrame) -> pd.DataFrame:
    priority = (
        df.groupby(
            [
                "postgraduate_university_name",
                "postgraduate_university_canonical",
                "destination_country_standardised",
                "postgraduate_university_standardisation_method",
            ],
            dropna=False,
        )
        .size()
        .reset_index(name="record_count")
        .rename(
            columns={
                "postgraduate_university_name": "source_university_name",
                "postgraduate_university_canonical": "suggested_canonical_name",
                "destination_country_standardised": "country_name",
                "postgraduate_university_standardisation_method": "standardisation_method",
            }
        )
    )

    priority["university_role"] = "postgraduate_destination"

    return priority


def build_undergraduate_priority(df: pd.DataFrame) -> pd.DataFrame:
    priority = (
        df.groupby(
            [
                "undergraduate_university_name",
                "undergraduate_university_canonical",
                "undergraduate_university_standardisation_method",
            ],
            dropna=False,
        )
        .size()
        .reset_index(name="record_count")
        .rename(
            columns={
                "undergraduate_university_name": "source_university_name",
                "undergraduate_university_canonical": "suggested_canonical_name",
                "undergraduate_university_standardisation_method": "standardisation_method",
            }
        )
    )

    priority["country_name"] = "Colombia"
    priority["university_role"] = "undergraduate_origin"

    return priority


def main() -> None:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run standardise_universities.py first."
        )

    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    df = pd.read_csv(INPUT_FILE)

    postgraduate_priority = build_postgraduate_priority(df)
    undergraduate_priority = build_undergraduate_priority(df)

    priority = pd.concat(
        [postgraduate_priority, undergraduate_priority],
        ignore_index=True,
    )

    priority["needs_review"] = priority["standardisation_method"].isin(
        ["normalised_title_case", "missing"]
    )

    priority = (
        priority
        .dropna(subset=["source_university_name"])
        .sort_values(
            ["needs_review", "record_count", "university_role", "country_name"],
            ascending=[False, False, True, True],
        )
        .reset_index(drop=True)
    )

    review_only_priority = priority[priority["needs_review"] == True].copy()

    priority.to_csv(OUTPUT_CSV, index=False, encoding="utf-8-sig")

    top_50 = review_only_priority.head(50).to_dict(orient="records")

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "output_csv": str(OUTPUT_CSV),
        "total_priority_rows": int(len(priority)),
        "postgraduate_priority_rows": int(len(postgraduate_priority)),
        "undergraduate_priority_rows": int(len(undergraduate_priority)),
        "top_50_university_review_items": top_50,
        "review_priority_rows": int(len(review_only_priority)),
        "already_mapped_priority_rows": int((priority["needs_review"] == False).sum()),
    }

    with OUTPUT_JSON.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)

    print("University review priority summary")
    print("----------------------------------")
    print(f"Total priority rows: {report['total_priority_rows']}")
    print(f"Postgraduate priority rows: {report['postgraduate_priority_rows']}")
    print(f"Undergraduate priority rows: {report['undergraduate_priority_rows']}")
    print(f"CSV output: {OUTPUT_CSV}")
    print(f"JSON report: {OUTPUT_JSON}")


if __name__ == "__main__":
    main()