import SectionCard from '../Components/dashboard/SectionCard'
import StatGrid from '../Components/dashboard/StatGrid'
import SeriesChart from '../Components/dashboard/SeriesChart'
import LogComparisonChart from '../Components/dashboard/LogComparisonChart'
import NotesPanel from '../Components/dashboard/NotesPanel'
import RecordsTable from '../Components/dashboard/RecordsTable'
import NoticeBanner from '../Components/dashboard/NoticeBanner'
import QuerySnapshot from '../Components/dashboard/QuerySnapshot'
import AppShell from '../Layouts/AppShell'

export default function ComparePage(props) {
  const { appName, navigation, sources, countryOptions, indicatorOptions, defaultYearRange, filters, compareDataset, comparisonPrinciples } = props

  const selectedCountries = countryOptions.filter((country) => (filters.countries || []).includes(country.code))
  const selectedIndicator = indicatorOptions.find((indicator) => indicator.key === filters.indicator)
  const selectedSources = sources.filter((source) => (filters.sourceKeys || []).includes(source.key))

  const querySnapshotItems = [
    { label: 'Countries', value: selectedCountries.map((country) => country.name).join(', ') || '—' },
    { label: 'Indicator', value: selectedIndicator?.label || filters.indicator },
    { label: 'Sources', value: selectedSources.map((source) => source.name).join(', ') || '—' },
    { label: 'Range', value: `${filters.startYear}–${filters.endYear}` },
  ]

  const recordColumns = [
    { key: 'source', label: 'Source' },
    { key: 'country_name', label: 'Country' },
    { key: 'normalized_indicator_label', label: 'Indicator' },
    { key: 'year', label: 'Year' },
    { key: 'formatted_value', label: 'Value' },
    { key: 'unit', label: 'Unit' },
    { key: 'notes', label: 'Notes' },
  ]

  const statusColumns = [
    { key: 'source', label: 'Source' },
    { key: 'country', label: 'Country' },
    { key: 'state', label: 'State' },
    { key: 'latest_year', label: 'Latest year' },
    { key: 'rows_returned', label: 'Rows returned' },
  ]

  return (
    <AppShell
      appName={appName}
      navigation={navigation}
      eyebrow="Cross-source analysis"
      title="Compare countries, years and providers"
      description="Review the same indicator across countries and data sources in one place."
    >
      <div className="page-stack">
        <NoticeBanner
          tone="warning"
          title="Comparison note"
          message="Similar indicators can still use different methodologies, update schedules, or definitions depending on the source."
        />

        {compareDataset.warning ? (
          <NoticeBanner tone="warning" title={compareDataset.warning.title} message={compareDataset.warning.message} />
        ) : null}

        <SectionCard title="Comparison filters" description="Choose one indicator, then select the countries and sources you want to compare.">
          <form className="filter-toolbar" method="GET" action="/compare">
            <div className="filter-toolbar__grid filter-toolbar__grid--compare-enhanced">
              <fieldset className="field compare-checklist-field">
                <legend>Countries</legend>
                <div className="compare-checklist compare-checklist--countries">
                  {countryOptions.map((country) => (
                    <label key={country.code} className="compare-checklist__item">
                      <input
                        type="checkbox"
                        name="countries[]"
                        value={country.code}
                        defaultChecked={(filters.countries || []).includes(country.code)}
                      />
                      <span className="compare-checklist__label">{country.name}</span>
                    </label>
                  ))}
                </div>
              </fieldset>

              <label className="field">
                <span>Indicator</span>
                <select name="indicator" defaultValue={filters.indicator}>
                  {indicatorOptions.map((indicator) => (
                    <option key={indicator.key} value={indicator.key}>{indicator.label}</option>
                  ))}
                </select>
              </label>

              <fieldset className="field compare-checklist-field">
                <legend>Sources</legend>
                <div className="compare-checklist compare-checklist--sources">
                  {sources.map((source) => (
                    <label key={source.key} className="compare-checklist__item">
                      <input
                        type="checkbox"
                        name="sourceKeys[]"
                        value={source.key}
                        defaultChecked={(filters.sourceKeys || []).includes(source.key)}
                      />
                      <span className="compare-checklist__label">{source.name}</span>
                    </label>
                  ))}
                </div>
              </fieldset>

              <label className="field">
                <span>Start year</span>
                <input type="number" name="startYear" min={defaultYearRange.from} max={defaultYearRange.to} defaultValue={filters.startYear} />
              </label>

              <label className="field">
                <span>End year</span>
                <input type="number" name="endYear" min={defaultYearRange.from} max={defaultYearRange.to} defaultValue={filters.endYear} />
              </label>
            </div>

            <div className="filter-toolbar__footer">
              <p>Select up to four countries and up to two sources for a side-by-side comparison.</p>
              <div className="button-row">
                <a className="button button--ghost" href="/compare">Reset</a>
                <button type="submit" className="button">Run comparison</button>
              </div>
            </div>
          </form>
        </SectionCard>

        <SectionCard title="Current comparison" description="Summary of the filters currently applied.">
          <QuerySnapshot items={querySnapshotItems} />
        </SectionCard>

        <SectionCard title="Comparison summary" description="Key metrics from the current comparison results.">
          <StatGrid items={compareDataset.summaryItems || []} />
        </SectionCard>

        {compareDataset.error ? (
          <SectionCard title="Data issue" description="One or more sources could not be processed for this request.">
            <NoticeBanner tone="error" title={compareDataset.error.title} message={compareDataset.error.message} />
          </SectionCard>
        ) : null}

        {compareDataset.emptyState ? (
          <SectionCard title="No results" description="No comparable records were returned for the current selection.">
            <NoticeBanner tone="idle" title={compareDataset.emptyState.title} message={compareDataset.emptyState.message} />
          </SectionCard>
        ) : null}

        <div className="two-column-grid two-column-grid--main">
          <SectionCard title="Comparison charts" description="Use the linear chart for absolute values and the log chart when one series is much larger than the rest.">
            <SeriesChart
              data={compareDataset.chartData || []}
              lines={compareDataset.chartLines || []}
              title="Linear scale"
              description="Best for reading absolute differences across countries and sources."
            />
            <div style={{ marginTop: '24px' }}>
              <LogComparisonChart data={compareDataset.chartData || []} lines={compareDataset.chartLines || []} />
            </div>
          </SectionCard>

          <SectionCard title="Methodology notes" description="Context and caveats to keep in mind when interpreting the results.">
            <NotesPanel
              title="Comparison notes"
              notes={[
                ...(comparisonPrinciples || []).map((item) => `${item.title}: ${item.description}`),
                ...(compareDataset.methodologyNotes || []),
              ]}
            />
          </SectionCard>
        </div>

        <SectionCard title="Provider status" description="Status of each provider-country query included in this comparison.">
          <RecordsTable rows={compareDataset.statusRows || []} columns={statusColumns} />
        </SectionCard>

        <SectionCard title="Comparison data" description="Detailed comparison records for the current selection.">
          <RecordsTable rows={compareDataset.records || []} columns={recordColumns} />
        </SectionCard>
      </div>
    </AppShell>
  )
}
