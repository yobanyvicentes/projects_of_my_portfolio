from pathlib import Path
import json
import pandas as pd


PROJECT_ROOT = Path(__file__).resolve().parents[3]
RAW_FILE = PROJECT_ROOT / "data" / "raw" / "Colfuturo_Seleccionados_2026-05-12.xlsx"
REPORTS_DIR = PROJECT_ROOT / "reports"


def profile_excel_file(file_path: Path) -> dict:
    """Return schema- and aggregate-level profiling only.

    Row samples are deliberately excluded because source workbooks may contain
    personally identifiable or otherwise sensitive record-level information.
    """
    if not file_path.exists():
        raise FileNotFoundError(f"File not found: {file_path}")

    REPORTS_DIR.mkdir(parents=True, exist_ok=True)

    excel = pd.ExcelFile(file_path)

    workbook_profile = {
        "file_name": file_path.name,
        "sheet_names": excel.sheet_names,
        "sheets": {},
    }

    for sheet_name in excel.sheet_names:
        df = pd.read_excel(file_path, sheet_name=sheet_name)

        sheet_profile = {
            "row_count": int(len(df)),
            "column_count": int(len(df.columns)),
            "columns": list(df.columns),
            "data_types": {col: str(dtype) for col, dtype in df.dtypes.items()},
            "missing_values": df.isna().sum().astype(int).to_dict(),
            "missing_percentage": (df.isna().mean() * 100).round(2).to_dict(),
            "exact_duplicate_rows": int(df.duplicated().sum()),
        }

        workbook_profile["sheets"][sheet_name] = sheet_profile

    return workbook_profile


def main() -> None:
    profile = profile_excel_file(RAW_FILE)
    output_path = REPORTS_DIR / "excel_profile_summary.json"

    with output_path.open("w", encoding="utf-8") as file:
        json.dump(profile, file, indent=2, ensure_ascii=False)

    print(f"Profile generated successfully: {output_path}")


if __name__ == "__main__":
    main()
