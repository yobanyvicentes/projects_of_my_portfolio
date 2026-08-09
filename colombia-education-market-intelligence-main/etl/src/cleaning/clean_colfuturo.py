#This script performs the following cleaning steps on the Seleccionados sheet:1
#1. Load the Seleccionados sheet
#2. Rename columns to English snake_case
#3. Trim spaces
#4. Normalise text
#5. Remove exact duplicates
#6. Create record_id
#7. Create person_hash
#8. Save cleaned CSV into data/processed/

from pathlib import Path
from datetime import datetime, timezone
import hashlib
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

RAW_FILE = PROJECT_ROOT / "data" / "raw" / "Colfuturo_Seleccionados_2026-05-12.xlsx"
PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REPORTS_DIR = PROJECT_ROOT / "reports"

OUTPUT_FILE = PROCESSED_DIR / "colfuturo_selected_cleaned.csv"
CLEANING_REPORT_FILE = REPORTS_DIR / "cleaning_report.json"

SOURCE_SHEET = "Seleccionados"


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


def load_raw_colfuturo_data(file_path: Path) -> pd.DataFrame:
    if not file_path.exists():
        raise FileNotFoundError(f"Raw file not found: {file_path}")

    return pd.read_excel(file_path, sheet_name=SOURCE_SHEET)


def clean_colfuturo_data(df: pd.DataFrame) -> tuple[pd.DataFrame, dict]:
    cleaned = df.copy()

    row_count_before = int(len(cleaned))
    source_columns = list(cleaned.columns)

    cleaned = cleaned.rename(columns=COLUMN_RENAME_MAP)

    expected_columns = list(COLUMN_RENAME_MAP.values())
    cleaned = cleaned[expected_columns]

    duplicate_rows_before = int(cleaned.duplicated().sum())

    cleaned = cleaned.drop_duplicates().reset_index(drop=True)

    row_count_after_deduplication = int(len(cleaned))

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
        cleaned[column] = cleaned[column].apply(clean_text)

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
        cleaned[column] = cleaned[column].apply(title_case_text)

    cleaned["application_year"] = pd.to_numeric(
        cleaned["application_year"],
        errors="coerce",
    ).astype("Int64")

    cleaned["person_hash"] = cleaned["person_name"].apply(generate_hash)
    cleaned["record_id"] = cleaned.apply(generate_record_id, axis=1)

    cleaned = cleaned.drop(columns=["person_name"])

    ordered_columns = [
        "record_id",
        "person_hash",
        "application_year",
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

    cleaned = cleaned[ordered_columns]

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "source_file": str(RAW_FILE),
        "source_sheet": SOURCE_SHEET,
        "output_file": str(OUTPUT_FILE),
        "rows_before_cleaning": row_count_before,
        "exact_duplicate_rows_removed": duplicate_rows_before,
        "rows_after_deduplication": row_count_after_deduplication,
        "rows_final": int(len(cleaned)),
        "source_columns": source_columns,
        "final_columns": list(cleaned.columns),
        "missing_values_final": cleaned.isna().sum().astype(int).to_dict(),
    }

    return cleaned, report


def save_cleaning_report(report: dict, output_path: Path) -> None:
    with output_path.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)


def main() -> None:
    PROCESSED_DIR.mkdir(parents=True, exist_ok=True)
    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    raw_df = load_raw_colfuturo_data(RAW_FILE)
    cleaned_df, report = clean_colfuturo_data(raw_df)

    cleaned_df.to_csv(OUTPUT_FILE, index=False, encoding="utf-8-sig")
    save_cleaning_report(report, CLEANING_REPORT_FILE)

    print("Cleaning summary")
    print("----------------")
    print(f"Rows before cleaning: {report['rows_before_cleaning']}")
    print(f"Exact duplicate rows removed: {report['exact_duplicate_rows_removed']}")
    print(f"Rows after deduplication: {report['rows_after_deduplication']}")
    print(f"Rows in final cleaned dataset: {report['rows_final']}")
    print(f"Cleaned dataset saved successfully: {OUTPUT_FILE}")
    print(f"Cleaning report saved successfully: {CLEANING_REPORT_FILE}")


if __name__ == "__main__":
    main()