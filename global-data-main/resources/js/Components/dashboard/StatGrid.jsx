export default function StatGrid(props) {
    const items = props.items || []
    const columnsClassName = props.columnsClassName || 'metric-grid metric-grid--compact'

    if (!items.length) {
        return null
    }

    return (
        <div className={columnsClassName}>
            {items.map((item, index) => (
                <article key={`${item.label}-${index}`} className="metric-card">
                    <span className="metric-card__label">{item.label}</span>
                    <strong className="metric-card__value">{item.value}</strong>
                    {item.description ? <p className="metric-card__description">{item.description}</p> : null}
                </article>
            ))}
        </div>
    )
}
