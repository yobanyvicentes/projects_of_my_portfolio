export default function SectionCard({ title, description, children, actions = null }) {
    return (
        <section className="section-card">
            <div className="section-card__header">
                <div>
                    <h2>{title}</h2>
                    {description ? <p className="section-card__description">{description}</p> : null}
                </div>
                {actions ? <div className="section-card__actions">{actions}</div> : null}
            </div>
            <div className="section-card__body">{children}</div>
        </section>
    )
}
