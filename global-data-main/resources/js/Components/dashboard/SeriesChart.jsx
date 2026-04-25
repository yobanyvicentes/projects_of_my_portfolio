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

function ChartEmptyState({ title, description }) {
    return (
        <div className="chart-empty-state">
            <strong>{title}</strong>
            <p>{description}</p>
        </div>
    )
}

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

export default function SeriesChart({
    data = [],
    lines = [],
    height = 320,
    xKey = 'year',
    title,
    description,
}) {
    if (!data.length || !lines.length) {
        return (
            <div className="chart-panel">
                {title ? <h3 className="chart-panel__title">{title}</h3> : null}
                {description ? <p className="chart-panel__description">{description}</p> : null}
                <ChartEmptyState
                    title="No chart data available"
                    description="The selected dataset does not contain enough plotted values for this visualization yet."
                />
            </div>
        )
    }

    return (
        <div className="chart-panel">
            {title ? <h3 className="chart-panel__title">{title}</h3> : null}
            {description ? <p className="chart-panel__description">{description}</p> : null}
            <div className="chart-panel__body" style={{ height }}>
                <ResponsiveContainer width="100%" height="100%">
                    <LineChart data={data} margin={{ top: 8, right: 20, bottom: 8, left: 8 }}>
                        <CartesianGrid vertical={false} />
                        <XAxis dataKey={xKey} />
                        <YAxis width={96} tickFormatter={formatAxisValue} tickMargin={10} />
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
