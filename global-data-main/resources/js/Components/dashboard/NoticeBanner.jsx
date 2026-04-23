export default function NoticeBanner({ tone = 'info', title, message }) {
    const toneClassName = `notice-panel notice-panel--${tone}`

    return (
        <div className={toneClassName}>
            <strong>{title}</strong>
            <p>{message}</p>
        </div>
    )
}
