# ETL Pipeline

The ETL code is organised as a research-oriented sequence rather than a production web backend.

Typical stages are:

1. `profiling/` — inspect workbook structure using schema and aggregate metrics only.
2. `cleaning/` — rename fields, normalise text, remove exact duplicates and remove names from processed outputs.
3. `standardisation/` — standardise core categories, countries and universities.
4. `reference/` — build local reference tables such as ROR organisation aliases.
5. `analysis/` — create research-specific aggregate tables and reconciliation outputs locally.

Generated datasets and reports are excluded from version control. Review `.gitignore` and `DATA_NOTICE.md` before adding new pipeline outputs.

Install dependencies with:

```bash
pip install -r etl/requirements.txt
```
