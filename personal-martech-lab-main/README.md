# Personal MarTech Lab — Public Implementation Snapshot

This folder is the public, disclosure-safe implementation snapshot for the **Personal MarTech Ecosystem** case study published at `https://yobany.top/case-studies/personal-martech-lab/`.

## Scope

The project treats a configured MarTech ecosystem as a Design Science artefact and separates public delivery, consent-aware telemetry, provider-managed raw data, canonical analytical evidence, analytical compute, reporting, and research governance.

Current implementation path:

`yobany.top → consent-aware GTM → GA4 → BigQuery raw export → governed conformance → versioned Parquet on Backblaze B2 → DuckDB → reproducible reporting / research analysis`

## Implemented and validated controls

- Strict Basic Consent Mode: optional analytics infrastructure is withheld until explicit analytics consent.
- Governed behavioural event taxonomy for `project_open`, `document_open`, `download`, `contact_intent`, and `outbound_click`.
- GA4 daily export to BigQuery as a provider-managed, non-canonical raw source.
- EX-001-v1 rule for excluding known implementation/debug traffic from canonical materialisation.
- Conformance-v2 for governed event semantics, deterministic canonical identity, quarantine of contract failures, and timezone-safe event-time reconstruction.
- Versioned Parquet as the canonical remote analytical format on Backblaze B2.
- DuckDB as the primary analytical/reconstruction engine over the remote canonical object.
- Minimum reproducible reporting surface with explicit metric eligibility and evidence boundaries.

## Accepted pre-baseline validation object

The first accepted canonical GA4 validation object was produced from `analytics_549039439.events_20260810`.

- Raw GA4 events: 86
- Technically eligible after EX-001: 52
- Governed-event candidates: 14
- Contract-PASS canonical observations: 13
- Quarantined contract failures: 1
- Unique canonical event IDs: 13
- Null canonical IDs: 0
- Accepted event distribution: `project_open=10`, `contact_intent=1`, `document_open=1`, `download=1`
- Evidence stage: `pre_baseline_validation`
- Conformance: `conformance-v2`
- Exclusion rule: `EX-001-v1`
- Remote object size: 5,818 bytes
- SHA-256: `d547c6892475d652bbe1778f84743d52b175a22b7713ca72297e185792539b97`

The accepted batch demonstrates pipeline correctness, semantic conformance, identity integrity, temporal correctness, remote reconstruction, and reporting reproducibility. It is **not** treated as a P1 longitudinal baseline and does not support claims of acquisition, engagement, SEO, conversion, or causal improvement.

## Public case-study interaction contract

For the case-study content object `CNT-024`:

- Public implementation evidence access uses the governed `outbound_click` event with `destination_class=github` and `cta_type=implementation_repository`.
- Return to the portfolio uses `project_open` with `content_id=CNT-024` and `component_id=PRJ-004`.
- Optional behavioural events are emitted only when analytics consent is granted.

## Disclosure boundary

This public snapshot intentionally excludes credentials, private Project Hub records, private Google Drive research-control documents, person-level data, pseudonymous GA4 user identifiers, and any secret-bearing configuration. The private release repository remains the operational source of the deployed portfolio; this public folder exists solely as an inspectable implementation/evidence surface.

## Current evidence frontier

The next stage is post-publication longitudinal observation. Technical operationalisation and measured marketing outcomes remain separate states. Search indexing, acquisition, engagement, and outcome comparisons are reported only when eligible observation windows and denominators exist.
