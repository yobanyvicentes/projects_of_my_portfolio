from pathlib import Path
from datetime import datetime, timezone
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = PROCESSED_DIR / "colfuturo_selected_cleaned.csv"
OUTPUT_FILE = PROCESSED_DIR / "colfuturo_selected_standardised.csv"
STANDARDISATION_REPORT_FILE = REPORTS_DIR / "core_standardisation_report.json"


GENDER_MAP = {
    "Masculino": "Male",
    "Femenino": "Female",
    "No Binario": "Non-binary",
}

SCHOLARSHIP_STATUS_MAP = {
    "Beneficiario": "Beneficiary",
    "Seleccionado": "Selected",
}

POSTGRADUATE_LEVEL_MAP = {
    "Maestría": "Master",
    "Maestría En Administración": "Master",
    "Doctorado": "PhD",
    "Doctorado En Administración": "PhD",
    "Especialización": "Specialisation",
    "Mba": "MBA",
    "Llm": "LLM",
}

ACADEMIC_AREA_MAP = {
    "Administración Y Negocios": "Business and Management",
    "Arquitectura Y Diseño": "Architecture and Design",
    "Artes": "Arts",
    "C.Agropecuarias": "Agricultural and Environmental Sciences",
    "C. Agropecuarias": "Agricultural and Environmental Sciences",
    "Ciencias Agropecuarias Y Del Medio Ambiente": "Agricultural and Environmental Sciences",
    "Ciencias Básicas": "Basic Sciences",
    "Ciencias De La Educación": "Education",
    "Educación": "Education",
    "Ciencias De La Salud": "Health Sciences",
    "Ciencias Del Medio Ambiente": "Environmental Sciences",
    "Ciencias Políticas Y Relaciones Internacionales": "Political Science and International Relations",
    "Derecho, Ciencias Políticas Y Relaciones Internacionales": "Law, Political Science and International Relations",
    "Ciencias Sociales": "Social Sciences",
    "Derecho": "Law",
    "Economía": "Economics",
    "Ingeniería": "Engineering",
    "Tecnologías De Información": "Information Technology",
}


def standardise_with_map(value: object, mapping: dict[str, str]) -> str | None:
    if pd.isna(value):
        return None

    text = str(value).strip()

    if not text:
        return None

    return mapping.get(text, text)


def standardise_core_fields(df: pd.DataFrame) -> tuple[pd.DataFrame, dict]:
    standardised = df.copy()

    before_unique_values = {
        "gender": sorted(standardised["gender"].dropna().unique().tolist()),
        "scholarship_status": sorted(standardised["scholarship_status"].dropna().unique().tolist()),
        "postgraduate_level": sorted(standardised["postgraduate_level"].dropna().unique().tolist()),
        "academic_area": sorted(standardised["academic_area"].dropna().unique().tolist()),
    }

    standardised["gender_standardised"] = standardised["gender"].apply(
        lambda value: standardise_with_map(value, GENDER_MAP)
    )

    standardised["scholarship_status_standardised"] = standardised["scholarship_status"].apply(
        lambda value: standardise_with_map(value, SCHOLARSHIP_STATUS_MAP)
    )

    standardised["postgraduate_level_standardised"] = standardised["postgraduate_level"].apply(
        lambda value: standardise_with_map(value, POSTGRADUATE_LEVEL_MAP)
    )

    standardised["academic_area_standardised"] = standardised["academic_area"].apply(
        lambda value: standardise_with_map(value, ACADEMIC_AREA_MAP)
    )

    after_unique_values = {
        "gender_standardised": sorted(standardised["gender_standardised"].dropna().unique().tolist()),
        "scholarship_status_standardised": sorted(
            standardised["scholarship_status_standardised"].dropna().unique().tolist()
        ),
        "postgraduate_level_standardised": sorted(
            standardised["postgraduate_level_standardised"].dropna().unique().tolist()
        ),
        "academic_area_standardised": sorted(
            standardised["academic_area_standardised"].dropna().unique().tolist()
        ),
    }

    unmapped_values = {
        "gender": sorted(
            set(before_unique_values["gender"]) - set(GENDER_MAP.keys())
        ),
        "scholarship_status": sorted(
            set(before_unique_values["scholarship_status"]) - set(SCHOLARSHIP_STATUS_MAP.keys())
        ),
        "postgraduate_level": sorted(
            set(before_unique_values["postgraduate_level"]) - set(POSTGRADUATE_LEVEL_MAP.keys())
        ),
        "academic_area": sorted(
            set(before_unique_values["academic_area"]) - set(ACADEMIC_AREA_MAP.keys())
        ),
    }

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "output_file": str(OUTPUT_FILE),
        "rows_processed": int(len(standardised)),
        "fields_standardised": [
            "gender",
            "scholarship_status",
            "postgraduate_level",
            "academic_area",
        ],
        "before_unique_values": before_unique_values,
        "after_unique_values": after_unique_values,
        "unmapped_values": unmapped_values,
        "missing_values_after_standardisation": {
            "gender_standardised": int(standardised["gender_standardised"].isna().sum()),
            "scholarship_status_standardised": int(
                standardised["scholarship_status_standardised"].isna().sum()
            ),
            "postgraduate_level_standardised": int(
                standardised["postgraduate_level_standardised"].isna().sum()
            ),
            "academic_area_standardised": int(
                standardised["academic_area_standardised"].isna().sum()
            ),
        },
    }

    return standardised, report


def save_report(report: dict, output_path: Path) -> None:
    with output_path.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)


def main() -> None:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run clean_colfuturo.py first."
        )

    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    df = pd.read_csv(INPUT_FILE)
    standardised_df, report = standardise_core_fields(df)

    standardised_df.to_csv(OUTPUT_FILE, index=False, encoding="utf-8-sig")
    save_report(report, STANDARDISATION_REPORT_FILE)

    print("Core field standardisation summary")
    print("-----------------------------------")
    print(f"Rows processed: {report['rows_processed']}")
    print(f"Output file: {OUTPUT_FILE}")
    print(f"Report file: {STANDARDISATION_REPORT_FILE}")

    print("\nUnmapped values:")
    for field, values in report["unmapped_values"].items():
        print(f"- {field}: {values}")


if __name__ == "__main__":
    main()