export default function QuerySnapshot({ items = [] }) {
    if (!items.length) {
        return null
    }

    return (
        <div className="query-snapshot">
            {items.map((item, index) => (
                <div key={`${item.label}-${index}`} className="query-snapshot__item">
                    <span className="query-snapshot__label">{item.label}</span>
                    <strong className="query-snapshot__value">{item.value}</strong>
                </div>
            ))}
        </div>
    )
}
