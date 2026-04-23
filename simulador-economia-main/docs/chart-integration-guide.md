# Chart integration guide

This branch adds reusable Blade partials to render charts with Chart.js.

## Files added

- `resources/views/partials/charts/chartjs-loader.blade.php`
- `resources/views/partials/charts/latest-run-dashboard.blade.php`
- `resources/views/partials/charts/simulation-results.blade.php`

## Where to include them

### 1) Dashboard

File to update:

- `resources/views/dashboard.blade.php`

Add this include right after the **Latest completed run** section and before **Recent Scenarios**:

```blade
@if ($hasLatestRun)
    @include('partials.charts.latest-run-dashboard', ['latestRun' => $latestRun])
@endif
```

## 2) Simulation results page

File to update:

- `resources/views/simulations/results.blade.php`

Add this include after the summary cards and before the table section:

```blade
@include('partials.charts.simulation-results', ['results' => $results])
```

## Why this approach

- No npm dependency is required.
- Chart.js is loaded from CDN once per page.
- The partials transform the existing Blade collections into chart-ready arrays.
- It keeps the current controller logic unchanged.

## Recommended chart placement

### Dashboard

- Market share line chart for the latest run
- HHI trend line chart for the latest run

### Simulation results

- Sales by period
- Market share by period
- Profit by period
- HHI by period

## Optional next step

If you want a more polished setup later, move the chart JavaScript into a dedicated Vite file and enable a script stack in `resources/views/layouts/app.blade.php`.
