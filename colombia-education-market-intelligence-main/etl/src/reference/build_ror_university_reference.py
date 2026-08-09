from pathlib import Path
from datetime import datetime, timezone
import json
import re
import unicodedata
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

RAW_ROR_FILE = PROJECT_ROOT / "data" / "raw" / "external" / "ror" / "v2.7" / "ror_v2_7.json"

REFERENCE_DIR = PROJECT_ROOT / "data" / "reference"
REPORTS_DIR = PROJECT_ROOT / "reports"

OUTPUT_FILE = REFERENCE_DIR / "universities_reference.csv"
REPORT_FILE = REPORTS_DIR / "ror_reference_build_report.json"


INSTITUTION_TYPES_TO_INCLUDE = {
    "Education",
    "Healthcare",
    "Facility",
    "Archive",
    "Nonprofit",
    "Government",
    "Company",
    "Other",
}


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


def load_ror_records(file_path: Path) -> list[dict]:
    if not file_path.exists():
        raise FileNotFoundError(
            f"ROR file not found: {file_path}. "
            "Place the ROR JSON file at data/raw/external/ror/v2.7/ror_v2_7.json"
        )

    with file_path.open("r", encoding="utf-8") as file:
        data = json.load(file)

    if isinstance(data, list):
        return data

    if isinstance(data, dict) and "items" in data:
        return data["items"]

    raise ValueError("Unsupported ROR JSON structure. Expected a list or a dict with an 'items' key.")


def get_country(record: dict) -> dict:
    locations = record.get("locations", [])

    if locations:
        geonames_details = locations[0].get("geonames_details", {}) or {}
        country = geonames_details.get("country_name")
        country_code = geonames_details.get("country_code")
        city = geonames_details.get("name")
    else:
        country = None
        country_code = None
        city = None

    return {
        "country_name": country,
        "country_code": country_code,
        "city": city,
    }


def get_names(record: dict) -> list[dict]:
    """
    ROR v2 uses a 'names' structure with name types.
    This function extracts all names and labels them as canonical, alias, acronym, or other.
    """
    names = []

    for name_item in record.get("names", []):
        value = name_item.get("value")
        types = name_item.get("types", []) or []

        if not value:
            continue

        if "ror_display" in types:
            name_type = "canonical"
        elif "alias" in types:
            name_type = "alias"
        elif "acronym" in types:
            name_type = "acronym"
        elif "label" in types:
            name_type = "label"
        else:
            name_type = "other"

        names.append(
            {
                "name": value,
                "name_type": name_type,
            }
        )

    return names


def get_canonical_name(record: dict) -> str | None:
    for name_item in record.get("names", []):
        types = name_item.get("types", []) or []
        if "ror_display" in types and name_item.get("value"):
            return name_item["value"]

    # Fallback for older or unexpected records
    if record.get("name"):
        return record["name"]

    names = get_names(record)
    if names:
        return names[0]["name"]

    return None


def record_to_reference_rows(record: dict) -> list[dict]:
    ror_id = record.get("id")
    canonical_name = get_canonical_name(record)
    country = get_country(record)

    organisation_types = record.get("types", []) or []
    organisation_type = organisation_types[0] if organisation_types else None

    if canonical_name is None:
        return []

    names = get_names(record)

    if not names:
        names = [{"name": canonical_name, "name_type": "canonical"}]

    rows = []

    for name_item in names:
        alias_name = name_item["name"]
        normalised_alias_name = normalise_text(alias_name)

        if normalised_alias_name is None:
            continue

        rows.append(
            {
                "source_system": "ROR",
                "ror_id": ror_id,
                "canonical_university_name": canonical_name,
                "alias_name": alias_name,
                "alias_type": name_item["name_type"],
                "normalised_alias_name": normalised_alias_name,
                "country_name": country["country_name"],
                "country_code": country["country_code"],
                "city": country["city"],
                "institution_type": organisation_type,
            }
        )

    return rows


def build_reference_table(records: list[dict]) -> pd.DataFrame:
    rows = []

    for record in records:
        rows.extend(record_to_reference_rows(record))

    reference = pd.DataFrame(rows)

    if reference.empty:
        return reference

    reference = (
        reference
        .drop_duplicates(
            subset=[
                "ror_id",
                "canonical_university_name",
                "alias_name",
                "country_code",
            ]
        )
        .sort_values(
            [
                "country_name",
                "canonical_university_name",
                "alias_type",
                "alias_name",
            ],
            na_position="last",
        )
        .reset_index(drop=True)
    )

    return reference


def main() -> None:
    REFERENCE_DIR.mkdir(parents=True, exist_ok=True)
    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    records = load_ror_records(RAW_ROR_FILE)
    reference = build_reference_table(records)

    reference.to_csv(OUTPUT_FILE, index=False, encoding="utf-8-sig")

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "source_file": str(RAW_ROR_FILE),
        "output_file": str(OUTPUT_FILE),
        "ror_records_loaded": int(len(records)),
        "reference_rows_created": int(len(reference)),
        "unique_ror_ids": int(reference["ror_id"].nunique()) if not reference.empty else 0,
        "unique_canonical_names": int(reference["canonical_university_name"].nunique()) if not reference.empty else 0,
        "unique_countries": int(reference["country_code"].nunique(dropna=True)) if not reference.empty else 0,
        "alias_type_counts": (
            reference["alias_type"].value_counts(dropna=False).to_dict()
            if not reference.empty
            else {}
        ),
        "institution_type_counts": (
            reference["institution_type"].value_counts(dropna=False).to_dict()
            if not reference.empty
            else {}
        ),
    }

    with REPORT_FILE.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)

    print("ROR university reference build summary")
    print("--------------------------------------")
    print(f"ROR records loaded: {report['ror_records_loaded']}")
    print(f"Reference rows created: {report['reference_rows_created']}")
    print(f"Unique ROR IDs: {report['unique_ror_ids']}")
    print(f"Unique canonical names: {report['unique_canonical_names']}")
    print(f"Unique countries: {report['unique_countries']}")
    print(f"Output file: {OUTPUT_FILE}")
    print(f"Report file: {REPORT_FILE}")


if __name__ == "__main__":
    main()