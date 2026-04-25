export default function RecordsTable({ rows = [], columns = [] }) {
    return (
        <div className="table-wrapper">
            <table className="data-table">
                <thead>
                    <tr>
                        {columns.map((column) => (
                            <th key={column.key}>{column.label}</th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {rows.length ? (
                        rows.map((row, rowIndex) => (
                            <tr key={row.id || rowIndex}>
                                {columns.map((column) => (
                                    <td key={`${rowIndex}-${column.key}`}>{row[column.key]}</td>
                                ))}
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td colSpan={columns.length || 1}>No rows available for the current dataset.</td>
                        </tr>
                    )}
                </tbody>
            </table>
        </div>
    )
}
