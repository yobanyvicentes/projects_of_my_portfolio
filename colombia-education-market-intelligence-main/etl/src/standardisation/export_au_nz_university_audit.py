from pathlib import Path
from datetime import datetime, timezone
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = PROCESSED_DIR / "colfuturo_selected_ror_reviewed.csv"

OUTPUT_CSV = REPORTS_DIR / "au_nz_university_audit.csv"
OUTPUT_JSON = REPORTS_DIR / "au_nz_university_audit_report.json"

TARGET_COUNTRIES = ["Australia", "New Zealand"]


def classify_review_status(row: pd.Series) -> str:
    if pd.isna(row.get("postgraduate_university_canonical")):
        return "missing_source"

    if row.get("postgraduate_ror_match_status") in ["auto_accepted", "review_accepted"]:
        return "matched"

    return "needs_review"


def main() -> None:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run apply_reviewed_ror_matches.py first."
        )

    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    df = pd.read_csv(INPUT_FILE)

    au_nz = df[df["destination_country_standardised"].isin(TARGET_COUNTRIES)].copy()

    audit = (
        au_nz.groupby(
            [
                "destination_country_standardised",
                "postgraduate_university_name",
                "postgraduate_university_canonical",
                "postgraduate_ror_id",
                "postgraduate_ror_canonical_name",
                "postgraduate_ror_alias_name",
                "postgraduate_ror_match_method",
                "postgraduate_ror_match_score",
                "postgraduate_ror_match_status",
            ],
            dropna=False,
        )
        .size()
        .reset_index(name="record_count")
        .sort_values(
            [
                "destination_country_standardised",
                "record_count",
                "postgraduate_university_name",
            ],
            ascending=[True, False, True],
        )
        .reset_index(drop=True)
    )

    audit["review_status"] = audit.apply(classify_review_status, axis=1)
    audit.insert(0, "manual_review_decision", "")
    audit.insert(1, "manual_review_notes", "")

    audit.to_csv(OUTPUT_CSV, index=False, encoding="utf-8-sig")

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "output_csv": str(OUTPUT_CSV),
        "target_countries": TARGET_COUNTRIES,
        "rows_in_au_nz_dataset": int(len(au_nz)),
        "unique_audit_rows": int(len(audit)),
        "records_by_country": au_nz["destination_country_standardised"].value_counts().to_dict(),
        "audit_rows_by_country": audit["destination_country_standardised"].value_counts().to_dict(),
        "review_status_counts": audit["review_status"].value_counts().to_dict(),
        "matched_record_count": int(
            audit.loc[audit["review_status"] == "matched", "record_count"].sum()
        ),
        "needs_review_record_count": int(
            audit.loc[audit["review_status"] == "needs_review", "record_count"].sum()
        ),
    }

    with OUTPUT_JSON.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)

    print("AU/NZ university audit summary")
    print("------------------------------")
    print(f"Rows in AU/NZ dataset: {report['rows_in_au_nz_dataset']}")
    print(f"Unique audit rows: {report['unique_audit_rows']}")
    print(f"Records by country: {report['records_by_country']}")
    print(f"Audit rows by country: {report['audit_rows_by_country']}")
    print(f"Review status counts: {report['review_status_counts']}")
    print(f"Matched record count: {report['matched_record_count']}")
    print(f"Needs review record count: {report['needs_review_record_count']}")
    print(f"CSV output: {OUTPUT_CSV}")
    print(f"JSON report: {OUTPUT_JSON}")


if __name__ == "__main__":
    main()