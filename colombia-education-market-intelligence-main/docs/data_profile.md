# Research Dataset Profiling Method

This document describes the **profiling method and expected schema** used by the research pipeline. It intentionally does not publish row-level source data, real-data profiling outputs, sample source records, or dataset-specific result counts.

## Expected analytical fields

The ingestion/cleaning workflow maps source fields into the following technical variables:

| Technical field | Analytical purpose |
|---|---|
| `application_year` | Time dimension |
| `person_name` | Temporary source identifier used during cleaning only |
| `gender` | Demographic dimension |
| `origin_region` | Origin-region analysis |
| `undergraduate_university_name` | Prior education institution |
| `undergraduate_programme_name` | Prior academic background |
| `postgraduate_university_name` | Destination institution |
| `destination_country` | Destination-country dimension |
| `destination_city` | Destination-city dimension |
| `postgraduate_level` | Programme level |
| `postgraduate_programme_name` | Postgraduate programme |
| `academic_area` | Academic field |
| `scholarship_status` | Programme/status category |
| `tags` | Optional source labels |

## Profiling checks

The profiler is designed to record schema- and aggregate-level information only:

- sheet names;
- row and column counts;
- column names;
- data types;
- missing-value counts and percentages;
- exact duplicate counts.

It deliberately **does not serialize sample rows** because source workbooks can contain personal or otherwise sensitive record-level information.

## Cleaning requirements

1. Rename source columns to stable English `snake_case` names.
2. Trim and normalise text fields.
3. Convert blank values into nulls where appropriate.
4. Remove exact duplicate rows.
5. Generate deterministic record identifiers for traceability.
6. Remove names from processed analytical outputs.
7. Treat deterministic person hashes as **pseudonymous identifiers**, not anonymous data.
8. Standardise categorical fields.
9. Standardise destination countries and codes.
10. Standardise institution names and support entity-resolution review.

## Privacy boundary

Personal names may be used locally during cleaning and duplicate detection, but they should not be published. Record-level person hashes should also remain local because deterministic hashes can still function as linkable pseudonymous identifiers.

Public outputs should use synthetic demonstration data or sufficiently aggregated, separately authorised research results.

See [`../DATA_NOTICE.md`](../DATA_NOTICE.md) for the repository's data-distribution boundary.
