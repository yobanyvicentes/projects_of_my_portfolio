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

export default function Home(props) {
    const {
        appName,
        navigation,
        sources,
        platformHighlights,
        normalizedModelFields,
        defaultCountries,
        defaultIndicators,
        defaultYearRange,
    } = props

    return (
        <AppShell
            appName={appName}
            navigation={navigation}
            eyebrow="Global Economic Analytics Platform"
            title="Explore global economic trends"
            description="Compare countries, review key indicators, and explore data from major international sources in one dashboard."
        >
            <div className="page-stack">
                <SectionCard title="Overview" description="Use this dashboard to compare economic indicators across countries and explore results from multiple data sources.">
                    <div className="metric-grid">
                        {platformHighlights.map((item) => (
                            <article key={item.label} className="metric-card">
                                <span className="metric-card__label">{item.label}</span>
                                <strong className="metric-card__value">{item.value}</strong>
                                <p className="metric-card__description">{item.description}</p>
                            </article>
                        ))}
                    </div>
                </SectionCard>

                <div className="two-column-grid">
                    <SectionCard title="Coverage" description="Start with the default countries and indicators below, or use the source pages to explore further.">
                        <div className="split-stat-list">
                            <div>
                                <span className="subtle-heading">Countries</span>
                                <ul className="bullet-list">
                                    {defaultCountries.map((country) => <li key={country.code}>{country.name}</li>)}
                                </ul>
                            </div>
                            <div>
                                <span className="subtle-heading">Indicators</span>
                                <ul className="bullet-list">
                                    {defaultIndicators.map((indicator) => <li key={indicator.key}>{indicator.label}</li>)}
                                </ul>
                            </div>
                            <div>
                                <span className="subtle-heading">Year range</span>
                                <p>{defaultYearRange.from} to {defaultYearRange.to}</p>
                            </div>
                        </div>
                    </SectionCard>

                    <SectionCard title="Available fields" description="These are some of the common fields used to organize and compare results across sources.">
                        <div className="field-chip-list">
                            {normalizedModelFields.map((field) => <span key={field} className="field-chip">{field}</span>)}
                        </div>
                    </SectionCard>
                </div>

                <SectionCard title="Data sources" description="Open any source workspace to explore data, review coverage notes, and run queries with the available filters.">
                    <div className="source-grid">
                        {sources.map((source) => (
                            <article key={source.key} className="source-card">
                                <div className="source-card__header">
                                    <span className="source-card__badge">{source.navLabel}</span>
                                    <h3>{source.name}</h3>
                                </div>
                                <p className="source-card__tagline">{source.tagline}</p>
                                <p className="source-card__description">{source.description}</p>
                                <ul className="bullet-list">
                                    {source.coverageBullets.map((bullet) => <li key={bullet}>{bullet}</li>)}
                                </ul>
                                <div className="source-card__footer">
                                    <a href={source.path}>Open workspace</a>
                                </div>
                            </article>
                        ))}
                    </div>
                </SectionCard>
            </div>
        </AppShell>
    )
}
