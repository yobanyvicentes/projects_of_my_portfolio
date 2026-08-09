from pathlib import Path
from datetime import datetime, timezone
import hashlib
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

RAW_FILE = PROJECT_ROOT / "data" / "raw" / "Colfuturo_Seleccionados_2026-05-12.xlsx"
FINAL_FILE = PROJECT_ROOT / "data" / "processed" / "colfuturo_selected_ror_reviewed.csv"

REPORTS_DIR = PROJECT_ROOT / "reports" / "reconciliation"

SUMMARY_FILE = REPORTS_DIR / "au_nz_raw_vs_final_summary_1992_2024.json"
DUPLICATES_FILE = REPORTS_DIR / "au_nz_removed_duplicate_records_1992_2024.csv"

SOURCE_SHEET = "Seleccionados"
START_YEAR = 1992
END_YEAR = 2024


COLUMN_RENAME_MAP = {
    "Prom": "application_year",
    "Nombre": "person_name",
    "Género": "gender",
    "Región Origen": "origin_region",
    "Univ. Pregrado": "undergraduate_university_name",
    "Pregrado": "undergraduate_programme_name",
    "Univ. Posgrado": "postgraduate_university_name",
    "País": "destination_country",
    "Ciudad Destino": "destination_city",
    "Tipo": "postgraduate_level",
    "Posgrado": "postgraduate_programme_name",
    "Área": "academic_area",
    "Estado": "scholarship_status",
    "Tags": "tags",
}


RAW_COUNTRY_MAP = {
    "Australia": "Australia",
    "Nueva Zelanda": "New Zealand",
    "New Zealand": "New Zealand",
}


def clean_text(value: object) -> str | None:
    if pd.isna(value):
        return None

    text = str(value).strip()

    if not text:
        return None

    return " ".join(text.split())


def title_case_text(value: object) -> str | None:
    text = clean_text(value)

    if text is None:
        return None

    return text.title()


def generate_hash(value: object) -> str | None:
    text = clean_text(value)

    if text is None:
        return None

    return hashlib.sha256(text.lower().encode("utf-8")).hexdigest()


def generate_record_id(row: pd.Series) -> str:
    key_fields = [
        row.get("application_year"),
        row.get("person_hash"),
        row.get("postgraduate_university_name"),
        row.get("destination_country"),
        row.get("postgraduate_level"),
        row.get("postgraduate_programme_name"),
        row.get("academic_area"),
        row.get("scholarship_status"),
    ]

    raw_key = "|".join(
        "" if pd.isna(value) else str(value).lower().strip()
        for value in key_fields
    )

    return hashlib.sha256(raw_key.encode("utf-8")).hexdigest()


def prepare_raw_data() -> pd.DataFrame:
    raw = pd.read_excel(RAW_FILE, sheet_name=SOURCE_SHEET)

    raw = raw.rename(columns=COLUMN_RENAME_MAP)
    raw = raw[list(COLUMN_RENAME_MAP.values())]

    text_columns = [
        "person_name",
        "gender",
        "origin_region",
        "undergraduate_university_name",
        "undergraduate_programme_name",
        "postgraduate_university_name",
        "destination_country",
        "destination_city",
        "postgraduate_level",
        "postgraduate_programme_name",
        "academic_area",
        "scholarship_status",
        "tags",
    ]

    for column in text_columns:
        raw[column] = raw[column].apply(clean_text)

    title_case_columns = [
        "gender",
        "origin_region",
        "destination_country",
        "destination_city",
        "postgraduate_level",
        "academic_area",
        "scholarship_status",
    ]

    for column in title_case_columns:
        raw[column] = raw[column].apply(title_case_text)

    raw["application_year"] = pd.to_numeric(
        raw["application_year"],
        errors="coerce",
    ).astype("Int64")

    raw["person_hash"] = raw["person_name"].apply(generate_hash)
    raw["record_id"] = raw.apply(generate_record_id, axis=1)

    raw["destination_country_standardised_raw"] = raw["destination_country"].map(
        RAW_COUNTRY_MAP
    )

    article_raw = raw[
        (raw["application_year"] >= START_YEAR)
        & (raw["application_year"] <= END_YEAR)
        & (raw["destination_country_standardised_raw"].isin(["Australia", "New Zealand"]))
    ].copy()

    return article_raw


def prepare_final_data() -> pd.DataFrame:
    final = pd.read_csv(FINAL_FILE)

    final["application_year"] = pd.to_numeric(
        final["application_year"],
        errors="coerce",
    ).astype("Int64")

    article_final = final[
        (final["application_year"] >= START_YEAR)
        & (final["application_year"] <= END_YEAR)
        & (final["destination_country_standardised"].isin(["Australia", "New Zealand"]))
    ].copy()

    return article_final


def build_duplicate_reconciliation(raw: pd.DataFrame, final: pd.DataFrame) -> pd.DataFrame:
    raw_counts = (
        raw.groupby(
            [
                "record_id",
                "person_hash",
                "application_year",
                "destination_country_standardised_raw",
                "postgraduate_university_name",
                "postgraduate_level",
                "postgraduate_programme_name",
                "academic_area",
                "scholarship_status",
            ],
            dropna=False,
        )
        .size()
        .reset_index(name="raw_count")
    )

    final_counts = (
        final.groupby(["record_id"], dropna=False)
        .size()
        .reset_index(name="final_count")
    )

    reconciliation = raw_counts.merge(
        final_counts,
        on="record_id",
        how="left",
    )

    reconciliation["final_count"] = reconciliation["final_count"].fillna(0).astype(int)
    reconciliation["removed_count"] = reconciliation["raw_count"] - reconciliation["final_count"]

    removed = reconciliation[reconciliation["removed_count"] > 0].copy()

    removed = removed.sort_values(
        ["destination_country_standardised_raw", "removed_count", "application_year"],
        ascending=[True, False, True],
    )

    return removed


def main() -> None:
    if not RAW_FILE.exists():
        raise FileNotFoundError(f"Raw file not found: {RAW_FILE}")

    if not FINAL_FILE.exists():
        raise FileNotFoundError(f"Final file not found: {FINAL_FILE}")

    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    raw = prepare_raw_data()
    final = prepare_final_data()

    removed = build_duplicate_reconciliation(raw, final)

    removed.to_csv(DUPLICATES_FILE, index=False, encoding="utf-8-sig")

    raw_country_counts = raw["destination_country_standardised_raw"].value_counts().to_dict()
    final_country_counts = final["destination_country_standardised"].value_counts().to_dict()

    summary = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "period": {
            "start_year": START_YEAR,
            "end_year": END_YEAR,
        },
        "raw_file": str(RAW_FILE),
        "final_file": str(FINAL_FILE),
        "raw_article_rows": int(len(raw)),
        "final_article_rows": int(len(final)),
        "difference_raw_minus_final": int(len(raw) - len(final)),
        "raw_country_counts": raw_country_counts,
        "final_country_counts": final_country_counts,
        "country_differences": {
            country: int(raw_country_counts.get(country, 0) - final_country_counts.get(country, 0))
            for country in ["Australia", "New Zealand"]
        },
        "removed_or_deduplicated_rows": int(removed["removed_count"].sum()),
        "removed_record_groups": int(len(removed)),
        "duplicates_output_file": str(DUPLICATES_FILE),
        "note": (
            "This reconciliation compares the original raw Excel extract with the final "
            "article dataset using the cleaning pipeline's record_id logic. Names are not "
            "included in the output; person_hash is used instead."
        ),
    }

    with SUMMARY_FILE.open("w", encoding="utf-8") as file:
        json.dump(summary, file, indent=2, ensure_ascii=False)

    print("Article dataset reconciliation summary")
    print("--------------------------------------")
    print(f"Raw article rows: {summary['raw_article_rows']}")
    print(f"Final article rows: {summary['final_article_rows']}")
    print(f"Difference raw minus final: {summary['difference_raw_minus_final']}")
    print(f"Raw country counts: {summary['raw_country_counts']}")
    print(f"Final country counts: {summary['final_country_counts']}")
    print(f"Country differences: {summary['country_differences']}")
    print(f"Removed/deduplicated rows: {summary['removed_or_deduplicated_rows']}")
    print(f"Removed record groups: {summary['removed_record_groups']}")
    print(f"Summary file: {SUMMARY_FILE}")
    print(f"Duplicate reconciliation file: {DUPLICATES_FILE}")


if __name__ == "__main__":
    main()