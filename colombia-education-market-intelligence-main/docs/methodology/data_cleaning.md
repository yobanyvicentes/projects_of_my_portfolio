# Data Cleaning Methodology

The cleaning stage is designed to make source records analytically consistent while keeping person-level data out of the public repository.

## Main transformations

1. Load the expected analytical sheet from a locally stored source workbook.
2. Rename source fields to stable English `snake_case` variables.
3. Trim leading/trailing whitespace and collapse repeated internal whitespace.
4. Convert empty strings to null values where appropriate.
5. Remove exact duplicate rows.
6. Standardise selected categorical fields.
7. Generate deterministic internal identifiers for traceability.
8. Remove direct personal names from processed analytical outputs.

## Pseudonymous identifiers

The current research workflow can derive a deterministic `person_hash` from a cleaned name to help detect repeated people across records. This identifier is **pseudonymous**, not anonymous: deterministic hashes may remain linkable or vulnerable to dictionary comparison when the source value comes from a predictable domain such as names.

For that reason, `person_hash` and other record-level outputs remain local and are excluded from the public repository.

## Public-repository rule

Only code, methodology documentation and explicitly synthetic sample data are version-controlled. Real source data, processed record-level data and generated reports belong in ignored paths described in `.gitignore` and `DATA_NOTICE.md`.
