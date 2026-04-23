export default function Dashboard(props) {
    return (
        <main style={{ minHeight: '100vh', padding: '32px' }}>
            <h1>Global Economic Dashboard</h1>
            <p>Hello from React + Inertia</p>

            <pre style={{ marginTop: '24px', background: '#f1f5f9', padding: '16px' }}>
                {JSON.stringify(props, null, 2)}
            </pre>
        </main>
    )
}
