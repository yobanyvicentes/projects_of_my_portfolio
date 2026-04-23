import { useMemo, useEffect, useState } from 'react'

function isCurrentPath(href) {
    if (typeof window === 'undefined') {
        return false
    }

    return window.location.pathname === href
}

function FooterCredits({ appName, year }) {
    const labels = [
        'Designed by',
        'Developed by',
        'Built by',
        'Created by',
        'Powered by',
        'Made with ❤️ by',
    ]

    const [index, setIndex] = useState(0)

    useEffect(() => {
        const interval = setInterval(() => {
            setIndex((prev) => (prev + 1) % labels.length)
        }, 2200)

        return () => clearInterval(interval)
    }, [])

    return (
        <div className="footer-center">
            <div className="footer-line">
                <span className="footer-label">{labels[index]}</span>
                <span>Yobany Vicentes - All rights reserved © {year}</span>
                <a href="/" className="footer-app">{appName}</a>
            </div>

            <div className="footer-links">
                <a href="https://www.linkedin.com/in/yobany-alberto-vicentes-jimenez/" target="_blank">LinkedIn</a>
                <a href="https://github.com/yobanyvicentes" target="_blank">GitHub</a>
                <a href="https://yobany.top" target="_blank">Portfolio</a>
            </div>
        </div>
    )
}

export default function AppShell({
    appName,
    navigation = [],
    title,
    eyebrow,
    description,
    children,
}) {
    const year = useMemo(() => new Date().getFullYear(), [])

    return (
        <div className="app-shell">
            <aside className="sidebar">
                <div className="brand-block">
                    <a className="brand-title" href="/">
                        {appName}
                    </a>
                    <p className="brand-copy">
                        Explore international economic indicators, compare sources, and review trends across countries and years.
                    </p>
                </div>

                <nav className="sidebar-nav" aria-label="Primary">
                    {navigation.map((item) => (
                        <a
                            key={item.key}
                            href={item.href}
                            className={`nav-link ${isCurrentPath(item.href) ? 'is-active' : ''}`}
                        >
                            {item.label}
                        </a>
                    ))}
                </nav>

                <div className="sidebar-note">
                    <span className="note-label">About this dashboard</span>
                    <strong>Compare sources with confidence</strong>
                    <p>
                        Use the source pages to explore individual datasets, then move to the comparison view to review patterns side by side.
                    </p>
                </div>
            </aside>

            <div className="page-shell">
                <header className="page-header">
                    <div>
                        {eyebrow ? <span className="page-eyebrow">{eyebrow}</span> : null}
                        <h1>{title}</h1>
                        {description ? <p className="page-description">{description}</p> : null}
                    </div>
                </header>

                <main className="page-content">{children}</main>

                <footer className="page-footer page-footer--credits">
                    <FooterCredits appName={appName} year={year} />
                </footer>
            </div>
        </div>
    )
}
