export default function NotesPanel({ notes = [], title = 'Analytical notes' }) {
    if (!notes.length) {
        return null
    }

    return (
        <div className="notes-panel">
            <h3 className="notes-panel__title">{title}</h3>
            <div className="principle-list">
                {notes.map((note, index) => (
                    <article key={`${index}-${note}`} className="principle-item">
                        <h3>Analytical note</h3>
                        <p>{note}</p>
                    </article>
                ))}
            </div>
        </div>
    )
}
