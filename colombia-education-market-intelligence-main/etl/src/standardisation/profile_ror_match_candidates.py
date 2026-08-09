from pathlib import Path
from datetime import datetime, timezone
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = REPORTS_DIR / "ror_university_match_candidates.csv"

OUTPUT_CSV = REPORTS_DIR / "ror_match_candidates_priority.csv"
OUTPUT_JSON = REPORTS_DIR / "ror_match_candidates_priority_report.json"


def classify_confidence(score: float) -> str:
    if score >= 97:
        return "high_confidence"
    if score >= 94:
        return "medium_confidence"
    return "low_confidence"


def main() -> None:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run match_universities_with_ror.py first."
        )

    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    candidates = pd.read_csv(INPUT_FILE)

    candidates["confidence_band"] = candidates["candidate_score"].apply(classify_confidence)

    priority = (
        candidates
        .sort_values(
            [
                "confidence_band",
                "candidate_score",
                "source_country_name",
                "source_canonical_name",
            ],
            ascending=[True, False, True, True],
        )
        .reset_index(drop=True)
    )

    priority.to_csv(OUTPUT_CSV, index=False, encoding="utf-8-sig")

    report = {
        "run_timestamp_utc": datetime.now(timezone.utc).isoformat(),
        "input_file": str(INPUT_FILE),
        "output_csv": str(OUTPUT_CSV),
        "total_candidate_rows": int(len(priority)),
        "confidence_band_counts": priority["confidence_band"].value_counts().to_dict(),
        "unique_source_universities": int(priority["source_canonical_name"].nunique(dropna=True)),
        "unique_candidate_ror_ids": int(priority["candidate_ror_id"].nunique(dropna=True)),
        "top_50_candidates": priority.head(50).to_dict(orient="records"),
        "notes": {
            "high_confidence": "Candidate score >= 97. These may be reviewed for possible auto-acceptance.",
            "medium_confidence": "Candidate score between 94 and 96.99. These require manual review.",
            "low_confidence": "Candidate score between 90 and 93.99. These should not be auto-accepted.",
        },
    }

    with OUTPUT_JSON.open("w", encoding="utf-8") as file:
        json.dump(report, file, indent=2, ensure_ascii=False)

    print("ROR match candidate priority summary")
    print("------------------------------------")
    print(f"Total candidate rows: {report['total_candidate_rows']}")
    print(f"Unique source universities: {report['unique_source_universities']}")
    print(f"Unique candidate ROR IDs: {report['unique_candidate_ror_ids']}")
    print(f"Confidence band counts: {report['confidence_band_counts']}")
    print(f"CSV output: {OUTPUT_CSV}")
    print(f"JSON report: {OUTPUT_JSON}")


if __name__ == "__main__":
    main()