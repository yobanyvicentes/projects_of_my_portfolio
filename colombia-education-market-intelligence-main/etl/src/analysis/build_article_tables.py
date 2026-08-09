from pathlib import Path
from datetime import datetime, timezone
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REPORTS_DIR = PROJECT_ROOT / "reports"
ARTICLE_TABLES_DIR = REPORTS_DIR / "article_tables"

INPUT_FILE = PROCESSED_DIR / "colfuturo_selected_ror_reviewed.csv"

SUMMARY_FILE = ARTICLE_TABLES_DIR / "au_nz_summary_1992_2024.csv"
BY_YEAR_FILE = ARTICLE_TABLES_DIR / "au_nz_by_year_1992_2024.csv"
TOP_AU_UNIVERSITIES_FILE = ARTICLE_TABLES_DIR / "top_au_universities_1992_2024.csv"
TOP_NZ_UNIVERSITIES_FILE = ARTICLE_TABLES_DIR / "top_nz_universities_1992_2024.csv"
LEVEL_SPLIT_FILE = ARTICLE_TABLES_DIR / "au_nz_postgraduate_level_split_1992_2024.csv"
REPORT_FILE = ARTICLE_TABLES_DIR / "article_tables_report.json"

TARGET_COUNTRIES = ["Australia", "New Zealand"]
START_YEAR = 1992
END_YEAR = 2024


def get_final_university_name(row: pd.Series) -> str | None:
    ror_name = row.get("postgraduate_ror_canonical_name")
    manual_name = row.get("postgraduate_university_canonical")

    if pd.notna(ror_name) and str(ror_name).strip():
        return str(ror_name).strip()

    if pd.notna(manual_name) and str(manual_name).strip():
        return str(manual_name).strip()

    return None


def load_filtered_data() -> pd.DataFrame:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run apply_reviewed_ror_matches.py first."
        )

    df = pd.read_csv(INPUT_FILE)

    df["application_year"] = pd.to_numeric(
        df["application_year"],
        errors="coerce",
    ).astype("Int64")

    filtered = df[
        (df["application_year"] >= START_YEAR)
        & (df["application_year"] <= END_YEAR)
        & (df["destination_country_standardised"].isin(TARGET_COUNTRIES))
    ].copy()

    filtered["final_postgraduate_university_name"] = filtered.apply(
        get_final_university_name,
        axis=1,
    )

    return filtered


def build_summary(df: pd.DataFrame) -> pd.DataFrame:
    summary = (
        df.groupby("destination_country_standardised")
        .agg(
            total_records=("record_id", "count"),
            unique_students=("person_hash", "nunique"),
            unique_destination_universities=("final_postgraduate_university_name", "nunique"),
            first_year=("application_year", "min"),
            last_year=("application_year", "max"),
        )
        .reset_index()
        .rename(columns={"destination_country_standardised": "destination_country"})
        .sort_values("total_records", ascending=False)
    )

    total = int(summary["total_records"].sum())

    summary["share_of_au_nz_records"] = (
        summary["total_records"] / total
    ).round(4)

    return summary


def build_by_year(df: pd.DataFrame) -> pd.DataFrame:
    by_year = (
        df.groupby(["application_year", "destination_country_standardised"])
        .size()
        .reset_index(name="record_count")
        .rename(columns={"destination_country_standardised": "destination_country"})
        .sort_values(["application_year", "destination_country"])
    )

    return by_year


def build_top_universities(df: pd.DataFrame, country: str) -> pd.DataFrame:
    country_df = df[df["destination_country_standardised"] == country].copy()

    top_universities = (
        country_df.groupby(
            [
                "final_postgraduate_university_name",
                "postgraduate_ror_id",
            ],
            dropna=False,
        )
        .agg(
            record_count=("record_id", "count"),
            unique_students=("person_hash", "nunique"),
            first_year=("application_year", "min"),
            last_year=("application_year", "max"),
        )
        .reset_index()
        .rename(
            columns={
                "final_postgraduate_university_name": "university_name",
                "postgraduate_ror_id": "ror_id",
            }
        )
        .sort_values(["record_count", "university_name"], ascending=[False, True])
    )

    total_country_records = int(country_df["record_id"].count())

    top_universities["share_of_country_records"] = (
        top_universities["record_count"] / total_country_records
    ).round(4)

    return top_universities


def build_level_split(df: pd.DataFrame) -> pd.DataFrame:
    level_split = (
        df.groupby(
            [
                "destination_country_standardised",
                "postgraduate_level_standardised",
            ],
            dropna=False,
        )
        .size()
        .reset_index(name="record_count")
        .rename(
            columns={
                "destination_country_standardised": "destination_country",
                "postgraduate_level_standardised": "postgraduate_level",
            }
        )
    )

    country_totals = (
        level_split.groupby("destination_country")["record_count"]
        .transform("sum")
    )

    level_split["share_within_country"] = (
        level_split["record_count"] / country_totals
    ).round(4)

    level_split = level_split.sort_values(
        ["destination_country", "record_count"],
        ascending=[True, False],
    )

    return level_split


def main() -> None:
    ARTICLE_TABLES_DIR.mkdir(parents=True, exist_ok=True)

    df = load_filtered_data()

    summary = build_summary(df)
    by_year = build_by_year(df)
    top_au_universities = build_top_universities(df, "Australia")
    top_nz_universities = build_top_universities(df, "New Zealand")
    level_split = build_level_split(df)

    summary.to_csv(SUMMARY_FILE, index=False, encoding="utf-8-sig")
    by_year.to_csv(BY_YEAR_FILE, index=False, encoding="utf-8-sig")
    top_au_universities.to_csv(TOP_AU_UNIVERSITIES_FILE, index=False, encoding="utf-8-sig")
    top_nz_universities.to_csv(TOP_NZ_UNIVERSITIES_FILE, index=False, encoding="utf-8-sig")
    level_split.to_csv(LEVEL_SPLIT_FILE, index=False, encoding="utf-8-sig")

    country_totals = summary.set_index("destination_country")["total_records"].to_dict()

    australia_total = int(country_totals.get("Australia", 0))
    new_zealand_total = int(country_totals.get("New Zealand", 0))

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "article_period": {
            "start_year": START_YEAR,
            "end_year": END_YEAR,
        },
        "target_countries": TARGET_COUNTRIES,
        "rows_in_article_dataset": int(len(df)),
        "country_totals": {
            "Australia": australia_total,
            "New Zealand": new_zealand_total,
        },
        "australia_to_new_zealand_ratio": round(
            australia_total / new_zealand_total,
            2,
        ) if new_zealand_total else None,
        "outputs": {
            "summary": str(SUMMARY_FILE),
            "by_year": str(BY_YEAR_FILE),
            "top_au_universities": str(TOP_AU_UNIVERSITIES_FILE),
            "top_nz_universities": str(TOP_NZ_UNIVERSITIES_FILE),
            "level_split": str(LEVEL_SPLIT_FILE),
        },
        "notes": (
            "University names use postgraduate_ror_canonical_name when available; "
            "otherwise they fall back to postgraduate_university_canonical."
        ),
    }

    with REPORT_FILE.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)

    print("Article table build summary")
    print("---------------------------")
    print(f"Rows in article dataset: {report['rows_in_article_dataset']}")
    print(f"Australia total: {australia_total}")
    print(f"New Zealand total: {new_zealand_total}")
    print(f"Australia/New Zealand ratio: {report['australia_to_new_zealand_ratio']}")
    print(f"Summary file: {SUMMARY_FILE}")
    print(f"By-year file: {BY_YEAR_FILE}")
    print(f"Top AU universities file: {TOP_AU_UNIVERSITIES_FILE}")
    print(f"Top NZ universities file: {TOP_NZ_UNIVERSITIES_FILE}")
    print(f"Level split file: {LEVEL_SPLIT_FILE}")
    print(f"Report file: {REPORT_FILE}")


if __name__ == "__main__":
    main()