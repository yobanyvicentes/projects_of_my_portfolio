import SectionCard from '../Components/dashboard/SectionCard'
import StatGrid from '../Components/dashboard/StatGrid'
import SeriesChart from '../Components/dashboard/SeriesChart'
import NotesPanel from '../Components/dashboard/NotesPanel'
import RecordsTable from '../Components/dashboard/RecordsTable'
import NoticeBanner from '../Components/dashboard/NoticeBanner'
import QuerySnapshot from '../Components/dashboard/QuerySnapshot'
import AppShell from '../Layouts/AppShell'

export default function ProviderLivePage(props) {
    const {
        appName,
        navigation,
        source,
        countryOptions,
        indicatorOptions,
        defaultYearRange,
        filters,
        dataset,
    } = props

    const records = dataset.records || []
    const chartPoints = dataset.chartPoints || []
    const chartData = chartPoints.map((point) => ({
        year: point.year,
        seriesValue: point.value,
    }))

    const selectedCountry = countryOptions.find((country) => country.code === filters.country)
    const selectedIndicator = indicatorOptions.find((indicator) => indicator.key === filters.indicator)

    const querySnapshotItems = [
        { label: 'Country', value: selectedCountry?.name || filters.country },
        { label: 'Indicator', value: selectedIndicator?.label || filters.indicator },
        { label: 'Range', value: `${filters.startYear}–${filters.endYear}` },
        { label: 'Rows returned', value: String(records.length) },
    ]

    const tableColumns = [
        { key: 'source', label: 'Source' },
        { key: 'normalized_indicator_label', label: 'Indicator' },
        { key: 'country_name', label: 'Country' },
        { key: 'year', label: 'Year' },
        { key: 'formatted_value', label: 'Value' },
        { key: 'unit', label: 'Unit' },
        { key: 'notes', label: 'Notes' },
    ]

    return (
        <AppShell
            appName={appName}
            navigation={navigation}
            eyebrow={source.name}
            title={`${source.name} data explorer`}
            description={`Explore ${source.name} data using the filters below.`}
        >
            <div className="page-stack">
                <NoticeBanner tone="warning" title="Methodology note" message={source.methodologyNotice} />

                {dataset.warning ? (
                    <NoticeBanner tone="warning" title={dataset.warning.title} message={dataset.warning.message} />
                ) : null}

                <SectionCard title={`${source.name} filters`} description="Select the country, indicator and time range you want to explore.">
                    <form className="filter-toolbar" method="GET" action={source.path}>
                        <div className="filter-toolbar__grid">
                            <label className="field">
                                <span>Country</span>
                                <select name="country" defaultValue={filters.country}>
                                    {countryOptions.map((country) => <option key={country.code} value={country.code}>{country.name}</option>)}
                                </select>
                            </label>
                            <label className="field">
                                <span>Indicator</span>
                                <select name="indicator" defaultValue={filters.indicator}>
                                    {indicatorOptions.map((indicator) => <option key={indicator.key} value={indicator.key}>{indicator.label}</option>)}
                                </select>
                            </label>
                            <label className="field">
                                <span>Start year</span>
                                <input name="startYear" type="number" min={defaultYearRange.from} max={defaultYearRange.to} defaultValue={filters.startYear} />
                            </label>
                            <label className="field">
                                <span>End year</span>
                                <input name="endYear" type="number" min={defaultYearRange.from} max={defaultYearRange.to} defaultValue={filters.endYear} />
                            </label>
                        </div>
                        <div className="filter-toolbar__footer">
                            <p>Adjust your filters to refine the results shown in the chart and table.</p>
                            <div className="button-row">
                                <a className="button button--ghost" href={source.path}>Reset</a>
                                <button type="submit" className="button">Run query</button>
                            </div>
                        </div>
                    </form>
                </SectionCard>

                <SectionCard title="Current selection" description="Summary of the filters currently applied.">
                    <QuerySnapshot items={querySnapshotItems} />
                </SectionCard>

                <SectionCard title="Summary" description="Key metrics based on your selected filters.">
                    <StatGrid items={dataset.summaryCards || []} />
                </SectionCard>

                {dataset.error ? (
                    <SectionCard title="Data issue" description="There was a problem retrieving data for this request.">
                        <NoticeBanner tone="error" title={dataset.error.title} message={dataset.error.message} />
                    </SectionCard>
                ) : null}

                {dataset.emptyState ? (
                    <SectionCard title="No results" description="No data matched the selected filters.">
                        <NoticeBanner tone="idle" title={dataset.emptyState.title} message={dataset.emptyState.message} />
                    </SectionCard>
                ) : null}

                <div className="two-column-grid two-column-grid--main">
                    <SectionCard title="Trend over time" description="See how the selected indicator changes over time.">
                        <SeriesChart
                            data={chartData}
                            lines={[{ dataKey: 'seriesValue', name: source.name }]}
                        />
                    </SectionCard>

                    <SectionCard title="Methodology" description="Important notes about how this data is defined and collected.">
                        <NotesPanel notes={dataset.methodologyNotes || []} />
                    </SectionCard>
                </div>

                <SectionCard title="Data table" description="Detailed records for the selected filters.">
                    <RecordsTable rows={records} columns={tableColumns} />
                </SectionCard>
            </div>
        </AppShell>
    )
}
