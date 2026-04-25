import { useState } from 'react'
import AppShell from '../Layouts/AppShell'

function SectionCard({ title, description, children }) {
    return (
        <section className="section-card">
            <div className="section-card__header">
                <div>
                    <h2>{title}</h2>
                    {description ? <p className="section-card__description">{description}</p> : null}
                </div>
            </div>
            <div className="section-card__body">{children}</div>
        </section>
    )
}

function DataStatePanel({ title, message, tone }) {
    return (
        <div className={`data-state data-state--${tone}`}>
            <strong>{title}</strong>
            <p>{message}</p>
        </div>
    )
}

function ChartPlaceholder() {
    return (
        <div className="chart-placeholder">
            <div className="chart-placeholder__copy">
                <h3>Historical time series</h3>
                <p>Line charts will connect to normalized provider responses in the next PRs.</p>
            </div>
            <div className="chart-placeholder__canvas" aria-hidden="true">
                <svg viewBox="0 0 420 180" role="presentation">
                    <line x1="20" y1="150" x2="400" y2="150" className="chart-axis" />
                    <line x1="20" y1="20" x2="20" y2="150" className="chart-axis" />
                    <polyline points="30,130 90,120 150,100 210,112 270,82 330,70 390,44" className="chart-line" />
                    <circle cx="390" cy="44" r="4" className="chart-point" />
                </svg>
            </div>
        </div>
    )
}

export default function ProviderPage(props) {
    const {
        appName,
        navigation,
        source,
        countryOptions,
        indicatorOptions,
        defaultYearRange,
        initialFilters,
        previewSummary,
        previewRows,
        uiStates,
    } = props

    const [filters, setFilters] = useState(initialFilters)
    const updateFilter = (key, value) => setFilters((previous) => ({ ...previous, [key]: value }))

    return (
        <AppShell appName={appName} navigation={navigation} eyebrow={source.name} title={`${source.name} workspace`} description={source.tagline}>
            <div className="page-stack">
                <div className="notice-panel notice-panel--warning">
                    <strong>Methodology notice</strong>
                    <p>{source.methodologyNotice}</p>
                </div>

                <SectionCard title="Filters" description="These controls are wired to the final data workflow and kept intentionally stable before live provider requests are added.">
                    <form className="filter-toolbar" onSubmit={(event) => event.preventDefault()}>
                        <div className="filter-toolbar__grid">
                            <label className="field">
                                <span>Country</span>
                                <select value={filters.country} onChange={(event) => updateFilter('country', event.target.value)}>
                                    {countryOptions.map((country) => <option key={country.code} value={country.code}>{country.name}</option>)}
                                </select>
                            </label>
                            <label className="field">
                                <span>Indicator</span>
                                <select value={filters.indicator} onChange={(event) => updateFilter('indicator', event.target.value)}>
                                    {indicatorOptions.map((indicator) => <option key={indicator.key} value={indicator.key}>{indicator.label}</option>)}
                                </select>
                            </label>
                            <label className="field">
                                <span>Start year</span>
                                <input type="number" min={defaultYearRange.from} max={defaultYearRange.to} value={filters.startYear} onChange={(event) => updateFilter('startYear', event.target.value)} />
                            </label>
                            <label className="field">
                                <span>End year</span>
                                <input type="number" min={defaultYearRange.from} max={defaultYearRange.to} value={filters.endYear} onChange={(event) => updateFilter('endYear', event.target.value)} />
                            </label>
                        </div>
                        <div className="filter-toolbar__footer">
                            <p>Filters are already structured for the final live query flow. This PR intentionally focuses on the stable analytics shell.</p>
                            <div className="button-row">
                                <button type="button" className="button button--ghost" onClick={() => setFilters(initialFilters)}>Reset</button>
                                <button type="submit" className="button">Apply filters</button>
                            </div>
                        </div>
                    </form>
                </SectionCard>

                <SectionCard title="Workspace summary" description="Provider-specific context for what this page is designed to answer.">
                    <div className="metric-grid metric-grid--compact">
                        {previewSummary.map((item) => (
                            <article key={item.label} className="metric-card">
                                <span className="metric-card__label">{item.label}</span>
                                <strong className="metric-card__value">{item.value}</strong>
                                <p className="metric-card__description">{item.description}</p>
                            </article>
                        ))}
                    </div>
                </SectionCard>

                <div className="two-column-grid two-column-grid--main">
                    <SectionCard title="Historical chart" description="Reserved for normalized time-series rendering.">
                        <ChartPlaceholder />
                    </SectionCard>
                    <SectionCard title="Friendly query states" description="Reusable UI blocks already cover idle, loading and failure messaging.">
                        <div className="state-stack">
                            <DataStatePanel tone="idle" title={uiStates.idle.title} message={uiStates.idle.message} />
                            <DataStatePanel tone="loading" title={uiStates.loading.title} message={uiStates.loading.message} />
                            <DataStatePanel tone="error" title={uiStates.error.title} message={uiStates.error.message} />
                        </div>
                    </SectionCard>
                </div>

                <SectionCard title="Series table" description="Table scaffolding is already aligned with the final normalized provider records.">
                    <div className="table-wrapper">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>Source</th>
                                    <th>Indicator</th>
                                    <th>Country</th>
                                    <th>Code</th>
                                    <th>Year</th>
                                    <th>Value</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                {previewRows.map((row, index) => (
                                    <tr key={`${row.country_code}-${row.year}-${index}`}>
                                        <td>{row.source}</td>
                                        <td>{row.indicator}</td>
                                        <td>{row.country}</td>
                                        <td>{row.country_code}</td>
                                        <td>{row.year}</td>
                                        <td>{row.value}</td>
                                        <td>{row.unit}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </SectionCard>

                <SectionCard title="Provider coverage" description="This page will remain explicit about what the source is good at and where caveats apply.">
                    <div className="coverage-block">
                        <p>{source.description}</p>
                        <ul className="bullet-list">
                            {source.coverageBullets.map((bullet) => <li key={bullet}>{bullet}</li>)}
                        </ul>
                        <p className="muted-copy">{source.plannedNextStep}</p>
                    </div>
                </SectionCard>
            </div>
        </AppShell>
    )
}
