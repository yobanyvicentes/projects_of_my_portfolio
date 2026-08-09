from pathlib import Path
from datetime import datetime, timezone
import hashlib
import json
import re
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

PROCESSED_DIR = PROJECT_ROOT / "data" / "processed"
REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = PROCESSED_DIR / "colfuturo_selected_country_standardised.csv"
OUTPUT_FILE = PROCESSED_DIR / "colfuturo_selected_university_standardised.csv"
UNIVERSITY_REVIEW_QUEUE_FILE = PROCESSED_DIR / "university_review_queue.csv"
UNIVERSITY_REPORT_FILE = REPORTS_DIR / "university_standardisation_report.json"


MANUAL_UNIVERSITY_ALIASES = {
        # Australia - contextual corrections for article dataset
    "University Of Victoria": "Victoria University",
    "Victoria University": "Victoria University",

    "Carnegie Mellon University Australia": "Carnegie Mellon University Australia",
    "Carnegie Mellon University": "Carnegie Mellon University Australia",

    
        # Australia - article-critical destination universities
    "The University Of Melbourne": "University of Melbourne",

    "The University Of Queensland": "The University of Queensland",
    "University Of Queensland": "The University of Queensland",
    "The University Of Queensland - Uq": "The University of Queensland",

    "University Of Technology - Sydney - Uts": "University of Technology Sydney",
    "University Of Technology Sydney": "University of Technology Sydney",

    "Royal Melbourne Institute Of Technology - Rmit University": "RMIT University",
    "Royal Melbourne Institute Of Technology - Rmit": "RMIT University",
    "Rmit University": "RMIT University",

    "The University Of Sydney": "University of Sydney",
    "University Of Sydney": "University of Sydney",
    "The University Of Sydney - Usyd": "University of Sydney",
    "U. Of Sydney": "University of Sydney",

    "Queensland University Of Technology": "Queensland University of Technology",
    "Queensland University Of Technology - Qut": "Queensland University of Technology",

    "University Of New South Wales": "University of New South Wales",
    "The University Of New South Wales": "University of New South Wales",
    "University Of New South Wales - Unsw": "University of New South Wales",
    "The University Of New South Wales - Unsw": "University of New South Wales",

    "The University Of Western Australia": "The University of Western Australia",
    "University Of Western Australia": "The University of Western Australia",
    "The University Of Western Australia - Uwa": "The University of Western Australia",

    "The University Of Adelaide": "University of Adelaide",
    "University Of Adelaide": "University of Adelaide",

    "The Australian National University": "Australian National University",
    "The Australian National University - Anu": "Australian National University",

    "James Cook University - Jcu": "James Cook University",

    "University Of Tasmania": "University of Tasmania",
    "University Of Tasmania - Utas": "University of Tasmania",

    "University Of South Australia": "University of South Australia",
    "University Of South Australia - Unisa": "University of South Australia",

    "Monash U.": "Monash University",

    "Central Queensland University (Cqu)": "Central Queensland University",

    # New Zealand - article-critical destination universities
    "The University Of Auckland": "University of Auckland",
    "University Of Auckland": "University of Auckland",

    "Auckland University Of Technology - Aut": "Auckland University of Technology",
    "Auckland University Of Technology": "Auckland University of Technology",

    "The University Of Waikato": "University of Waikato",
    "University Of Waikato": "University of Waikato",
    # United Kingdom
    "Ucl": "University College London",
    "U.C.L.": "University College London",
    "University College London (Ucl)": "University College London",
    "London School Of Economics": "London School of Economics and Political Science",
    "London School Of Economics And Political Science": "London School of Economics and Political Science",
    "Lse": "London School of Economics and Political Science",
    "L.S.E.": "London School of Economics and Political Science",

    # United States
    "Mit": "Massachusetts Institute of Technology",
    "M.I.T.": "Massachusetts Institute of Technology",
    "Columbia University In The City Of New York": "Columbia University",

    # Colombia
    "Universidad De Los Andes": "Universidad de los Andes",
    "Universidad Nacional De Colombia": "Universidad Nacional de Colombia",
    "Pontificia Universidad Javeriana": "Pontificia Universidad Javeriana",
    "Pontificia Universidad Javeriana (Puj), Seccional Cali": "Pontificia Universidad Javeriana Cali",

    # Mexico
    "Universidad Nacional Autónoma De México - Unam": "Universidad Nacional Autónoma de México",
    "Unam": "Universidad Nacional Autónoma de México",

        # High-impact postgraduate destinations
    "University College London - Ucl": "University College London",
    "University College London Ucl": "University College London",

    "London School Of Economics & Political Science - Lse": "London School of Economics and Political Science",
    "London School Of Economics And Political Science - Lse": "London School of Economics and Political Science",
    "London School Of Economics & Political Science": "London School of Economics and Political Science",

    "New York University - Nyu": "New York University",
    "Technische Universität München - Tum": "Technical University of Munich",
    "The University Of Queensland - Uq": "The University of Queensland",
    "University Of Melbourne": "University of Melbourne",
    "University Of Oxford": "University of Oxford",
    "University Of Chicago": "University of Chicago",
    "University Of Manchester": "University of Manchester",
    "University Of Technology - Sydney - Uts": "University of Technology Sydney",
    "Mcgill University": "McGill University",

    # High-impact Colombian undergraduate origin universities
    "Universidad Nacional De Colombia - Unal": "Universidad Nacional de Colombia",
    "Universidad Nacional De Colombia (Unal), Sede Medellín": "Universidad Nacional de Colombia",
    "Pontificia Universidad Javeriana (Puj)": "Pontificia Universidad Javeriana",
    "Universidad De Antioquía": "Universidad de Antioquia",
    "Universidad De Antioquia (Ua)": "Universidad de Antioquia",
    "Universidad Del Rosario": "Universidad del Rosario",
    "Colegio Mayor De Nuestra Señora Del Rosario": "Universidad del Rosario",
    "Universidad De La Sabana": "Universidad de La Sabana",
    "Universidad Industrial De Santander - Uis": "Universidad Industrial de Santander",
    "Universidad Industrial De Santander": "Universidad Industrial de Santander",
    "Universidad Del Norte - Uninorte": "Universidad del Norte",
    "Universidad Del Norte": "Universidad del Norte",
    "Universidad Del Valle - Univalle": "Universidad del Valle",
    "Universidad Del Valle": "Universidad del Valle",
    "Universidad Pontificia Bolivariana - Upb": "Universidad Pontificia Bolivariana",
}


LEGAL_SUFFIX_PATTERNS = [
    r"\bltd\b",
    r"\binc\b",
    r"\bllc\b",
]


def clean_university_name(value: object) -> str | None:
    if pd.isna(value):
        return None

    text = str(value).strip()

    if not text:
        return None

    text = " ".join(text.split())
    text = text.replace("–", "-").replace("—", "-")

    return text


def normalise_for_matching(value: object) -> str | None:
    text = clean_university_name(value)

    if text is None:
        return None

    text = text.lower()
    text = re.sub(r"[.,;:()]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()

    for pattern in LEGAL_SUFFIX_PATTERNS:
        text = re.sub(pattern, "", text)

    text = re.sub(r"\s+", " ", text).strip()

    return text


def to_readable_name(value: object) -> str | None:
    text = clean_university_name(value)

    if text is None:
        return None

    return text.title()


def canonicalise_university_name(value: object) -> tuple[str | None, str]:
    readable_name = to_readable_name(value)

    if readable_name is None:
        return None, "missing"

    if readable_name in MANUAL_UNIVERSITY_ALIASES:
        return MANUAL_UNIVERSITY_ALIASES[readable_name], "manual_alias"

    return readable_name, "normalised_title_case"


def generate_university_id(canonical_name: object, country: object | None = None) -> str | None:
    if pd.isna(canonical_name) or canonical_name is None:
        return None

    country_part = "" if pd.isna(country) or country is None else str(country).strip().lower()
    raw_key = f"{str(canonical_name).strip().lower()}|{country_part}"

    return hashlib.sha256(raw_key.encode("utf-8")).hexdigest()


def standardise_university_column(
    df: pd.DataFrame,
    source_column: str,
    canonical_column: str,
    id_column: str,
    method_column: str,
    country_column: str | None = None,
) -> pd.DataFrame:
    output = df.copy()

    canonical_results = output[source_column].apply(canonicalise_university_name)

    output[canonical_column] = canonical_results.apply(lambda item: item[0])
    output[method_column] = canonical_results.apply(lambda item: item[1])

    if country_column and country_column in output.columns:
        output[id_column] = output.apply(
            lambda row: generate_university_id(row[canonical_column], row[country_column]),
            axis=1,
        )
    else:
        output[id_column] = output[canonical_column].apply(generate_university_id)

    return output


def build_review_queue(df: pd.DataFrame) -> pd.DataFrame:
    """
    Creates a review queue for university names that were not manually matched.

    This is intentionally simple for the MVP. Later, this queue can be improved
    with RapidFuzz similarity scores and candidate canonical matches.
    """
    postgraduate_review = (
        df[
            [
                "postgraduate_university_name",
                "postgraduate_university_canonical",
                "postgraduate_university_standardisation_method",
                "destination_country_standardised",
            ]
        ]
        .drop_duplicates()
        .rename(
            columns={
                "postgraduate_university_name": "source_university_name",
                "postgraduate_university_canonical": "suggested_canonical_name",
                "postgraduate_university_standardisation_method": "standardisation_method",
                "destination_country_standardised": "country_name",
            }
        )
    )

    postgraduate_review["university_role"] = "postgraduate_destination"

    undergraduate_review = (
        df[
            [
                "undergraduate_university_name",
                "undergraduate_university_canonical",
                "undergraduate_university_standardisation_method",
            ]
        ]
        .drop_duplicates()
        .rename(
            columns={
                "undergraduate_university_name": "source_university_name",
                "undergraduate_university_canonical": "suggested_canonical_name",
                "undergraduate_university_standardisation_method": "standardisation_method",
            }
        )
    )

    undergraduate_review["country_name"] = "Colombia"
    undergraduate_review["university_role"] = "undergraduate_origin"

    review_queue = pd.concat(
        [postgraduate_review, undergraduate_review],
        ignore_index=True,
    )

    review_queue = review_queue[
        review_queue["standardisation_method"].isin(
            ["normalised_title_case", "missing"]
        )
    ]

    review_queue = (
        review_queue
        .dropna(subset=["source_university_name"])
        .drop_duplicates()
        .sort_values(["university_role", "country_name", "source_university_name"])
        .reset_index(drop=True)
    )

    return review_queue


def standardise_universities(df: pd.DataFrame) -> tuple[pd.DataFrame, pd.DataFrame, dict]:
    standardised = df.copy()

    standardised = standardise_university_column(
        standardised,
        source_column="postgraduate_university_name",
        canonical_column="postgraduate_university_canonical",
        id_column="postgraduate_university_id",
        method_column="postgraduate_university_standardisation_method",
        country_column="destination_country_standardised",
    )

    standardised = standardise_university_column(
        standardised,
        source_column="undergraduate_university_name",
        canonical_column="undergraduate_university_canonical",
        id_column="undergraduate_university_id",
        method_column="undergraduate_university_standardisation_method",
    )

    review_queue = build_review_queue(standardised)

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "output_file": str(OUTPUT_FILE),
        "review_queue_file": str(UNIVERSITY_REVIEW_QUEUE_FILE),
        "rows_processed": int(len(standardised)),
        "postgraduate_source_university_count": int(
            standardised["postgraduate_university_name"].nunique(dropna=True)
        ),
        "postgraduate_canonical_university_count": int(
            standardised["postgraduate_university_canonical"].nunique(dropna=True)
        ),
        "undergraduate_source_university_count": int(
            standardised["undergraduate_university_name"].nunique(dropna=True)
        ),
        "undergraduate_canonical_university_count": int(
            standardised["undergraduate_university_canonical"].nunique(dropna=True)
        ),
        "manual_alias_mapped_postgraduate_rows": int(
            (
                standardised["postgraduate_university_standardisation_method"]
                == "manual_alias"
            ).sum()
        ),
        "manual_alias_mapped_undergraduate_rows": int(
            (
                standardised["undergraduate_university_standardisation_method"]
                == "manual_alias"
            ).sum()
        ),
        "review_queue_rows": int(len(review_queue)),
        "missing_postgraduate_university_rows": int(
            standardised["postgraduate_university_canonical"].isna().sum()
        ),
        "missing_undergraduate_university_rows": int(
            standardised["undergraduate_university_canonical"].isna().sum()
        ),
    }

    return standardised, review_queue, report


def save_report(report: dict, output_path: Path) -> None:
    with output_path.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)


def main() -> None:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run standardise_countries.py first."
        )

    PROCESSED_DIR.mkdir(parents=True, exist_ok=True)
    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    df = pd.read_csv(INPUT_FILE)

    standardised_df, review_queue, report = standardise_universities(df)

    standardised_df.to_csv(OUTPUT_FILE, index=False, encoding="utf-8-sig")
    review_queue.to_csv(UNIVERSITY_REVIEW_QUEUE_FILE, index=False, encoding="utf-8-sig")
    save_report(report, UNIVERSITY_REPORT_FILE)

    print("University standardisation summary")
    print("----------------------------------")
    print(f"Rows processed: {report['rows_processed']}")
    print(
        "Postgraduate source universities: "
        f"{report['postgraduate_source_university_count']}"
    )
    print(
        "Postgraduate canonical universities: "
        f"{report['postgraduate_canonical_university_count']}"
    )
    print(
        "Undergraduate source universities: "
        f"{report['undergraduate_source_university_count']}"
    )
    print(
        "Undergraduate canonical universities: "
        f"{report['undergraduate_canonical_university_count']}"
    )
    print(f"Review queue rows: {report['review_queue_rows']}")
    print(f"Output file: {OUTPUT_FILE}")
    print(f"Review queue file: {UNIVERSITY_REVIEW_QUEUE_FILE}")
    print(f"Report file: {UNIVERSITY_REPORT_FILE}")


if __name__ == "__main__":
    main()