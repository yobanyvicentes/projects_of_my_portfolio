# Data Notice

## Third-party source data

This repository contains software and methodological documentation for a research data pipeline. It does not grant permission to copy, redistribute or republish any third-party dataset used with the pipeline.

The research workflow has been used with data obtained from COLFUTURO. Anyone wishing to reproduce that analysis must obtain the relevant source data independently and comply with COLFUTURO's applicable terms of use, privacy requirements and intellectual-property conditions.

## What is intentionally excluded

The public repository should not contain:

- original COLFUTURO Excel/CSV exports;
- processed record-level datasets derived from those exports;
- names or other directly identifying student data;
- pseudonymous person hashes at record level;
- manually reviewed record-level matching outputs derived from source data;
- generated analytical CSV/JSON reports containing results from the real dataset.

These paths are excluded through `.gitignore` and should remain local to the researcher.

## Synthetic demonstration data

Files under `data/sample/` are synthetic demonstration data. They are manually created fictional examples and are not samples, extracts or transformations of real COLFUTURO records.

## Software licence boundary

The repository's software licence applies only to original code and documentation authored for this project. It does not apply to third-party data, names, trademarks, database rights or other content owned by external organisations.
