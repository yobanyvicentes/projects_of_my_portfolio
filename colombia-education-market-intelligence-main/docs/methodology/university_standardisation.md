# University Standardisation Methodology

Institution names in real-world education datasets frequently contain spelling variants, acronyms, translated names and legacy labels. The pipeline therefore treats university standardisation as an entity-resolution problem rather than a simple text-formatting step.

## Workflow

1. Clean whitespace and punctuation consistently.
2. Apply curated aliases for known high-impact variants.
3. Create canonical-name candidates.
4. Build a local organisation reference from ROR data.
5. Attempt country-constrained exact matching against canonical names and aliases.
6. Generate fuzzy candidates for unresolved institutions.
7. Keep fuzzy matches in a manual review queue rather than automatically applying uncertain matches.
8. Preserve match method, score and review status for auditability.

## Public-data boundary

The matching code and methodology are version-controlled. Real review queues, source institution frequency tables and matched record-level outputs are generated locally and excluded from the public repository because they are derived from the research source dataset.

ROR reference data must be obtained according to ROR's own distribution terms; generated local reference tables are excluded from version control in this project.
