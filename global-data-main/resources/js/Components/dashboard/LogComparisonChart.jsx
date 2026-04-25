import {
    CartesianGrid,
    Legend,
    Line,
    LineChart,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis,
} from 'recharts'

const LINE_COLORS = ['#2563eb', '#dc2626', '#16a34a', '#9333ea', '#ea580c', '#0891b2']

function formatAxisValue(value) {
    if (value === null || value === undefined || Number.isNaN(Number(value))) {
        return '—'
    }

    return new Intl.NumberFormat('en', {
        notation: 'compact',
        maximumFractionDigits: 1,
    }).format(Number(value))
}

function formatTooltipValue(value) {
    if (value === null || value === undefined || Number.isNaN(Number(value))) {
        return '—'
    }

    return new Intl.NumberFormat('en', {
        maximumFractionDigits: 2,
    }).format(Number(value))
}

export default function LogComparisonChart({ data = [], lines = [] }) {
    const filteredData = data.filter((row) => lines.some((line) => Number(row[line.dataKey]) > 0))

    if (!filteredData.length || !lines.length) {
        return (
            <div className="chart-panel">
                <h3 className="chart-panel__title">Multi-source comparison · Log scale</h3>
                <p className="chart-panel__description">This chart needs positive values to render a logarithmic scale.</p>
                <div className="chart-empty-state">
                    <strong>No chart data available</strong>
                    <p>The current dataset does not contain enough positive plotted values for this chart.</p>
                </div>
            </div>
        )
    }

    return (
        <div className="chart-panel">
            <h3 className="chart-panel__title">Multi-source comparison · Log scale</h3>
            <p className="chart-panel__description">Better for comparing smaller series when one provider-country combination is much larger than the rest.</p>
            <div className="chart-panel__body" style={{ height: 320 }}>
                <ResponsiveContainer width="100%" height="100%">
                    <LineChart data={filteredData} margin={{ top: 8, right: 20, bottom: 8, left: 8 }}>
                        <CartesianGrid vertical={false} />
                        <XAxis dataKey="year" />
                        <YAxis width={96} tickFormatter={formatAxisValue} tickMargin={10} scale="log" domain={['auto', 'auto']} allowDataOverflow={false} />
                        <Tooltip formatter={(value) => formatTooltipValue(value)} />
                        <Legend />
                        {lines.map((line, index) => (
                            <Line
                                key={line.dataKey}
                                type="monotone"
                                dataKey={line.dataKey}
                                name={line.name}
                                dot={false}
                                connectNulls
                                stroke={LINE_COLORS[index % LINE_COLORS.length]}
                                activeDot={{ r: 5 }}
                                strokeWidth={2.5}
                            />
                        ))}
                    </LineChart>
                </ResponsiveContainer>
            </div>
        </div>
    )
}
