# Colombia Education Market Intelligence | Research Data Pipeline

Python-based research data pipeline for profiling, cleaning, standardising and reconciling postgraduate mobility data used in a portfolio and academic research workflow.

## Scope

The implemented project focuses on reproducible data preparation rather than a finished web platform. It demonstrates:

- Excel/CSV ingestion and profiling
- reproducible cleaning and deduplication
- categorical field standardisation
- country standardisation
- university/entity resolution workflows
- ROR-based matching and manual review queues
- audit and reconciliation scripts
- research-oriented documentation

Potential dashboards, APIs and web applications are future extensions and are not presented as implemented features.

## Data and licensing notice

This repository does **not** redistribute the original COLFUTURO dataset or public research outputs generated from identifiable or record-level source data.

Users who want to reproduce the research workflow with COLFUTURO data must obtain the source data independently and comply with the applicable COLFUTURO terms, privacy requirements and intellectual-property conditions.

The software license in this repository applies to the original code and documentation created for this project. It does not grant rights over third-party datasets.

See [`DATA_NOTICE.md`](DATA_NOTICE.md) for details.

## Privacy approach

The source workflow may process personal names for cleaning and duplicate detection. Names are removed from processed analytical outputs and replaced, where needed internally, with deterministic hashes.

These hashes should be treated as **pseudonymous identifiers**, not as irreversible anonymisation. Record-level hashes and other person-level outputs are intentionally excluded from the public repository.

## Repository structure

```text
.
├── data/
│   ├── raw/          # local source data; never versioned
│   ├── processed/    # generated analytical data; never versioned
│   └── sample/       # synthetic demonstration data only
├── etl/
│   └── src/
│       ├── cleaning/
│       ├── profiling/
│       ├── standardisation/
│       └── analysis/
├── docs/             # methodology and technical documentation
├── reports/          # generated locally; real-data outputs are ignored
└── README.md
```

## Synthetic sample

`data/sample/colfuturo_sample_synthetic.csv` contains entirely fictional records created only to illustrate the expected analytical structure. It is not extracted from COLFUTURO and must not be interpreted as real student data.

## Technologies

- Python
- pandas
- openpyxl
- RapidFuzz
- JSON/CSV processing
- ROR-based organisation matching
- Git/GitHub

## Local setup

```bash
git clone https://github.com/yobanyvicentes/colombia-education-market-intelligence.git
cd colombia-education-market-intelligence
python -m venv .venv
pip install -r etl/requirements.txt
```

The production research pipeline expects source files in `data/raw/`, which is intentionally excluded from version control. Generated processed datasets and reports are also excluded.

## Research use case

The project was developed to support analysis of Colombian postgraduate destination patterns, including comparative interest in Australia and New Zealand, while maintaining a reproducible data-cleaning and entity-resolution methodology.

## Portfolio value

This repository demonstrates practical work in data engineering, data quality, entity resolution, research reproducibility and analytical workflow design without publishing third-party source datasets or person-level research data.

## Author

**Yobany Vicentes**  
New Zealand

- Portfolio: https://yobany.top
- LinkedIn: https://www.linkedin.com/in/yobany-alberto-vicentes-jimenez/
