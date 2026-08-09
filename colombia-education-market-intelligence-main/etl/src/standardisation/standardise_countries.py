from pathlib import Path
from datetime import datetime, timezone
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = PROCESSED_DIR / "colfuturo_selected_standardised.csv"
OUTPUT_FILE = PROCESSED_DIR / "colfuturo_selected_country_standardised.csv"
COUNTRY_REPORT_FILE = REPORTS_DIR / "country_standardisation_report.json"


COUNTRY_REFERENCE = {
    "Alemania": {
        "country": "Germany",
        "iso2": "DE",
        "iso3": "DEU",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Argentina": {
        "country": "Argentina",
        "iso2": "AR",
        "iso3": "ARG",
        "region": "South America",
        "continent": "Americas",
    },
    "Australia": {
        "country": "Australia",
        "iso2": "AU",
        "iso3": "AUS",
        "region": "Oceania",
        "continent": "Oceania",
    },
    "Austria": {
        "country": "Austria",
        "iso2": "AT",
        "iso3": "AUT",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Bélgica": {
        "country": "Belgium",
        "iso2": "BE",
        "iso3": "BEL",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Brasil": {
        "country": "Brazil",
        "iso2": "BR",
        "iso3": "BRA",
        "region": "South America",
        "continent": "Americas",
    },
    "Canadá": {
        "country": "Canada",
        "iso2": "CA",
        "iso3": "CAN",
        "region": "North America",
        "continent": "Americas",
    },
    "Chile": {
        "country": "Chile",
        "iso2": "CL",
        "iso3": "CHL",
        "region": "South America",
        "continent": "Americas",
    },
    "China": {
        "country": "China",
        "iso2": "CN",
        "iso3": "CHN",
        "region": "East Asia",
        "continent": "Asia",
    },
    "Colombia": {
        "country": "Colombia",
        "iso2": "CO",
        "iso3": "COL",
        "region": "South America",
        "continent": "Americas",
    },
    "Corea Del Sur": {
        "country": "South Korea",
        "iso2": "KR",
        "iso3": "KOR",
        "region": "East Asia",
        "continent": "Asia",
    },
    "Dinamarca": {
        "country": "Denmark",
        "iso2": "DK",
        "iso3": "DNK",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "España": {
        "country": "Spain",
        "iso2": "ES",
        "iso3": "ESP",
        "region": "Southern Europe",
        "continent": "Europe",
    },
    "Estados Unidos": {
        "country": "United States",
        "iso2": "US",
        "iso3": "USA",
        "region": "North America",
        "continent": "Americas",
    },
    "Finlandia": {
        "country": "Finland",
        "iso2": "FI",
        "iso3": "FIN",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Francia": {
        "country": "France",
        "iso2": "FR",
        "iso3": "FRA",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Holanda": {
        "country": "Netherlands",
        "iso2": "NL",
        "iso3": "NLD",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "India": {
        "country": "India",
        "iso2": "IN",
        "iso3": "IND",
        "region": "South Asia",
        "continent": "Asia",
    },
    "Irlanda": {
        "country": "Ireland",
        "iso2": "IE",
        "iso3": "IRL",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Italia": {
        "country": "Italy",
        "iso2": "IT",
        "iso3": "ITA",
        "region": "Southern Europe",
        "continent": "Europe",
    },
    "Japón": {
        "country": "Japan",
        "iso2": "JP",
        "iso3": "JPN",
        "region": "East Asia",
        "continent": "Asia",
    },
    "México": {
        "country": "Mexico",
        "iso2": "MX",
        "iso3": "MEX",
        "region": "North America",
        "continent": "Americas",
    },
    "Noruega": {
        "country": "Norway",
        "iso2": "NO",
        "iso3": "NOR",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Nueva Zelanda": {
        "country": "New Zealand",
        "iso2": "NZ",
        "iso3": "NZL",
        "region": "Oceania",
        "continent": "Oceania",
    },
    "Portugal": {
        "country": "Portugal",
        "iso2": "PT",
        "iso3": "PRT",
        "region": "Southern Europe",
        "continent": "Europe",
    },
    "Reino Unido": {
        "country": "United Kingdom",
        "iso2": "GB",
        "iso3": "GBR",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Singapur": {
        "country": "Singapore",
        "iso2": "SG",
        "iso3": "SGP",
        "region": "Southeast Asia",
        "continent": "Asia",
    },
    "Suecia": {
        "country": "Sweden",
        "iso2": "SE",
        "iso3": "SWE",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Suiza": {
        "country": "Switzerland",
        "iso2": "CH",
        "iso3": "CHE",
        "region": "Western Europe",
        "continent": "Europe",
    },
        "Bosnia Y Herzegovina": {
        "country": "Bosnia and Herzegovina",
        "iso2": "BA",
        "iso3": "BIH",
        "region": "Southern Europe",
        "continent": "Europe",
    },
    "Bulgaria": {
        "country": "Bulgaria",
        "iso2": "BG",
        "iso3": "BGR",
        "region": "Eastern Europe",
        "continent": "Europe",
    },
    "Chequia": {
        "country": "Czechia",
        "iso2": "CZ",
        "iso3": "CZE",
        "region": "Eastern Europe",
        "continent": "Europe",
    },
    "República Checa": {
        "country": "Czechia",
        "iso2": "CZ",
        "iso3": "CZE",
        "region": "Eastern Europe",
        "continent": "Europe",
    },
    "Costa Rica": {
        "country": "Costa Rica",
        "iso2": "CR",
        "iso3": "CRI",
        "region": "Central America",
        "continent": "Americas",
    },
    "Cuba": {
        "country": "Cuba",
        "iso2": "CU",
        "iso3": "CUB",
        "region": "Caribbean",
        "continent": "Americas",
    },
    "Dubái - Emiratos Árabes Unidos": {
        "country": "United Arab Emirates",
        "iso2": "AE",
        "iso3": "ARE",
        "region": "Middle East",
        "continent": "Asia",
    },
    "Emiratos Árabes Unidos": {
        "country": "United Arab Emirates",
        "iso2": "AE",
        "iso3": "ARE",
        "region": "Middle East",
        "continent": "Asia",
    },
    "Ecuador": {
        "country": "Ecuador",
        "iso2": "EC",
        "iso3": "ECU",
        "region": "South America",
        "continent": "Americas",
    },
    "Egipto": {
        "country": "Egypt",
        "iso2": "EG",
        "iso3": "EGY",
        "region": "North Africa",
        "continent": "Africa",
    },
    "Escocia": {
        "country": "United Kingdom",
        "iso2": "GB",
        "iso3": "GBR",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Gales": {
        "country": "United Kingdom",
        "iso2": "GB",
        "iso3": "GBR",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Uk": {
        "country": "United Kingdom",
        "iso2": "GB",
        "iso3": "GBR",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Eslovenia": {
        "country": "Slovenia",
        "iso2": "SI",
        "iso3": "SVN",
        "region": "Southern Europe",
        "continent": "Europe",
    },
    "Estonia": {
        "country": "Estonia",
        "iso2": "EE",
        "iso3": "EST",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Germany": {
        "country": "Germany",
        "iso2": "DE",
        "iso3": "DEU",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Ghent, Belgica": {
        "country": "Belgium",
        "iso2": "BE",
        "iso3": "BEL",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Hong Kong": {
        "country": "Hong Kong",
        "iso2": "HK",
        "iso3": "HKG",
        "region": "East Asia",
        "continent": "Asia",
    },
    "Hungría": {
        "country": "Hungary",
        "iso2": "HU",
        "iso3": "HUN",
        "region": "Eastern Europe",
        "continent": "Europe",
    },
    "Irelanda": {
        "country": "Ireland",
        "iso2": "IE",
        "iso3": "IRL",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Islandia": {
        "country": "Iceland",
        "iso2": "IS",
        "iso3": "ISL",
        "region": "Northern Europe",
        "continent": "Europe",
    },
    "Israel": {
        "country": "Israel",
        "iso2": "IL",
        "iso3": "ISR",
        "region": "Middle East",
        "continent": "Asia",
    },
    "Luxemburgo": {
        "country": "Luxembourg",
        "iso2": "LU",
        "iso3": "LUX",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Líbano": {
        "country": "Lebanon",
        "iso2": "LB",
        "iso3": "LBN",
        "region": "Middle East",
        "continent": "Asia",
    },
    "Malasia": {
        "country": "Malaysia",
        "iso2": "MY",
        "iso3": "MYS",
        "region": "Southeast Asia",
        "continent": "Asia",
    },
    "Mónaco": {
        "country": "Monaco",
        "iso2": "MC",
        "iso3": "MCO",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Nicaragua": {
        "country": "Nicaragua",
        "iso2": "NI",
        "iso3": "NIC",
        "region": "Central America",
        "continent": "Americas",
    },
    "Países Bajos": {
        "country": "Netherlands",
        "iso2": "NL",
        "iso3": "NLD",
        "region": "Western Europe",
        "continent": "Europe",
    },
    "Perú": {
        "country": "Peru",
        "iso2": "PE",
        "iso3": "PER",
        "region": "South America",
        "continent": "Americas",
    },
    "Polonia": {
        "country": "Poland",
        "iso2": "PL",
        "iso3": "POL",
        "region": "Eastern Europe",
        "continent": "Europe",
    },
    "Puerto Rico": {
        "country": "Puerto Rico",
        "iso2": "PR",
        "iso3": "PRI",
        "region": "Caribbean",
        "continent": "Americas",
    },
    "República De Singapur": {
        "country": "Singapore",
        "iso2": "SG",
        "iso3": "SGP",
        "region": "Southeast Asia",
        "continent": "Asia",
    },
    "Rusia": {
        "country": "Russia",
        "iso2": "RU",
        "iso3": "RUS",
        "region": "Eastern Europe",
        "continent": "Europe",
    },
    "Sudáfrica": {
        "country": "South Africa",
        "iso2": "ZA",
        "iso3": "ZAF",
        "region": "Southern Africa",
        "continent": "Africa",
    },
    "Thuwal Arabia Saudita": {
        "country": "Saudi Arabia",
        "iso2": "SA",
        "iso3": "SAU",
        "region": "Middle East",
        "continent": "Asia",
    },
}


def normalise_country_key(value: object) -> str | None:
    if pd.isna(value):
        return None

    text = str(value).strip()

    if not text:
        return None

    return " ".join(text.split()).title()


def lookup_country(value: object) -> dict:
    country_key = normalise_country_key(value)

    if country_key is None:
        return {
            "country": None,
            "iso2": None,
            "iso3": None,
            "region": None,
            "continent": None,
            "matched": False,
        }

    reference = COUNTRY_REFERENCE.get(country_key)

    if reference is None:
        return {
            "country": country_key,
            "iso2": None,
            "iso3": None,
            "region": None,
            "continent": None,
            "matched": False,
        }

    return {
        **reference,
        "matched": True,
    }


def standardise_countries(df: pd.DataFrame) -> tuple[pd.DataFrame, dict]:
    standardised = df.copy()

    country_results = standardised["destination_country"].apply(lookup_country)

    standardised["destination_country_standardised"] = country_results.apply(lambda item: item["country"])
    standardised["destination_country_iso2"] = country_results.apply(lambda item: item["iso2"])
    standardised["destination_country_iso3"] = country_results.apply(lambda item: item["iso3"])
    standardised["destination_region"] = country_results.apply(lambda item: item["region"])
    standardised["destination_continent"] = country_results.apply(lambda item: item["continent"])
    standardised["destination_country_matched"] = country_results.apply(lambda item: item["matched"])

    unmatched_countries = sorted(
        standardised.loc[
            standardised["destination_country_matched"] == False,
            "destination_country",
        ]
        .dropna()
        .unique()
        .tolist()
    )

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "output_file": str(OUTPUT_FILE),
        "rows_processed": int(len(standardised)),
        "unique_source_countries": int(standardised["destination_country"].nunique(dropna=True)),
        "matched_country_rows": int(standardised["destination_country_matched"].sum()),
        "unmatched_country_rows": int((standardised["destination_country_matched"] == False).sum()),
        "unmatched_countries": unmatched_countries,
        "standardised_country_count": int(
            standardised["destination_country_standardised"].nunique(dropna=True)
        ),
    }

    return standardised, report


def save_report(report: dict, output_path: Path) -> None:
    with output_path.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)


def main() -> None:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run standardise_core_fields.py first."
        )

    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    df = pd.read_csv(INPUT_FILE)
    standardised_df, report = standardise_countries(df)

    standardised_df.to_csv(OUTPUT_FILE, index=False, encoding="utf-8-sig")
    save_report(report, COUNTRY_REPORT_FILE)

    print("Country standardisation summary")
    print("--------------------------------")
    print(f"Rows processed: {report['rows_processed']}")
    print(f"Unique source countries: {report['unique_source_countries']}")
    print(f"Matched country rows: {report['matched_country_rows']}")
    print(f"Unmatched country rows: {report['unmatched_country_rows']}")
    print(f"Unmatched countries: {report['unmatched_countries']}")
    print(f"Output file: {OUTPUT_FILE}")
    print(f"Report file: {COUNTRY_REPORT_FILE}")


if __name__ == "__main__":
    main()