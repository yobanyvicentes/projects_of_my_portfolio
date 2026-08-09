from pathlib import Path
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]

REPORTS_DIR = PROJECT_ROOT / "reports"

INPUT_FILE = REPORTS_DIR / "ror_match_candidates_priority.csv"
OUTPUT_FILE = REPORTS_DIR / "ror_high_confidence_review.csv"


def main() -> None:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(
            f"Input file not found: {INPUT_FILE}. Run profile_ror_match_candidates.py first."
        )

    candidates = pd.read_csv(INPUT_FILE)

    high_confidence = candidates[
        candidates["confidence_band"] == "high_confidence"
    ].copy()

    high_confidence.insert(0, "review_decision", "")
    high_confidence.insert(1, "review_notes", "")

    high_confidence.to_csv(OUTPUT_FILE, index=False, encoding="utf-8-sig")

    print("High-confidence ROR review export")
    print("---------------------------------")
    print(f"Rows exported: {len(high_confidence)}")
    print(f"Output file: {OUTPUT_FILE}")


if __name__ == "__main__":
    main()