# Colombia Education Market Intelligence | Research Data Pipeline

Python-based research data pipeline for profiling, cleaning, standardising and reconciling postgraduate mobility data used in an academic research workflow.

This folder is a **sanitised public snapshot** of the project. It contains code, methodology and synthetic demonstration data, while the working research repository and record-level research data remain private.

## Scope

The implemented project focuses on reproducible data preparation rather than a finished web platform. It includes:

- Excel/CSV ingestion and profiling
- reproducible cleaning and deduplication
- categorical field standardisation
- country standardisation
- university/entity resolution workflows
- ROR-based matching and manual review queues
- audit and reconciliation scripts
- research-oriented documentation

Dashboards, APIs and web applications are possible extensions, but they are not presented here as implemented components of this pipeline.

## Data and licensing notice

This public snapshot does **not** redistribute the original COLFUTURO dataset or record-level outputs generated from identifiable source data.

Anyone wishing to reproduce the research workflow with COLFUTURO data must obtain the relevant source data independently and comply with the applicable COLFUTURO terms, privacy requirements and intellectual-property conditions.

The software licence applies only to original code and documentation created for this project. It does not grant rights over third-party datasets.

See [`DATA_NOTICE.md`](DATA_NOTICE.md) for details.

## Privacy approach

The working research pipeline may process personal names for cleaning and duplicate detection. Names are removed from analytical outputs and, where deterministic linkage is required internally, may be replaced by hashes.

Those hashes are treated as **pseudonymous identifiers**, not irreversible anonymisation. Record-level hashes and other person-level outputs are intentionally excluded from this public snapshot.

## Project structure

```text
.
├── data/
│   ├── raw/          # local source data; never published
│   ├── processed/    # generated analytical data; never published
│   └── sample/       # synthetic demonstration data only
├── etl/
│   └── src/
│       ├── cleaning/
│       ├── profiling/
│       ├── standardisation/
│       └── analysis/
├── docs/             # methodology and technical documentation
├── reports/          # generated locally; real-data outputs are excluded
└── README.md
```

## Synthetic sample

`data/sample/colfuturo_sample_synthetic.csv` contains fictional records created only to illustrate the expected analytical structure. It is not an extract or transformation of real COLFUTURO records.

## Technologies

- Python
- pandas
- openpyxl
- RapidFuzz
- JSON/CSV processing
- ROR-based organisation matching
- Git/GitHub

## Running the public snapshot locally

Clone the public portfolio repository and enter this project folder:

```bash
git clone https://github.com/yobanyvicentes/projects_of_my_portfolio.git
cd projects_of_my_portfolio/colombia-education-market-intelligence-main
python -m venv .venv
```

Activate the virtual environment using the command appropriate for the operating system, then install the ETL dependencies:

```bash
pip install -r etl/requirements.txt
```

The production research workflow expects source files in `data/raw/`, which is intentionally excluded from the public snapshot. Generated processed datasets and real-data reports are also excluded.

## Research use case

The project supports analysis of Colombian postgraduate destination patterns, including comparative interest in Australia and New Zealand, while preserving a reproducible data-cleaning and entity-resolution methodology.

## What this snapshot demonstrates

The published code provides evidence of practical work in:

- data engineering
- data quality and profiling
- entity resolution
- research reproducibility
- audit-oriented analytical workflows
- privacy-aware publication of research code

## Author

**Yobany Vicentes**  
New Zealand

- Portfolio: https://yobany.top
- LinkedIn: https://www.linkedin.com/in/yobany-alberto-vicentes-jimenez/
