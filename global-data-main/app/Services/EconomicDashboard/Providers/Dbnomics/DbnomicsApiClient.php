<?php

namespace App\Services\EconomicDashboard\Providers\Dbnomics;

use Illuminate\Http\Client\Factory as HttpFactory;
use RuntimeException;

class DbnomicsApiClient
{
    public function __construct(
        protected HttpFactory $http,
    ) {
    }

    public function fetchSeries(string $seriesId): array
    {
        $config = config('economic-dashboard.providers.dbnomics', []);

        $response = $this->http
            ->accept('text/csv')
            ->timeout((int) ($config['timeout_seconds'] ?? 12))
            ->retry((int) ($config['retry_times'] ?? 1), (int) ($config['retry_sleep_milliseconds'] ?? 200))
            ->get(rtrim((string) ($config['base_url'] ?? 'https://api.db.nomics.world/v22'), '/').'/series', [
                'series_ids' => $seriesId,
                'observations' => 1,
                'format' => 'csv',
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'DBnomics API request failed with HTTP %d.',
                $response->status(),
            ));
        }

        $body = trim((string) $response->body());

        if ($body === '') {
            throw new RuntimeException('DBnomics API returned an empty CSV payload.');
        }

        return $this->parseCsv($body);
    }

    protected function parseCsv(string $csv): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $csv) ?: [];

        if ($lines === []) {
            return [];
        }

        $header = str_getcsv((string) array_shift($lines));
        $columnCount = count($header);
        $rows = [];

        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }

            $values = str_getcsv($line);
            $values = array_pad($values, $columnCount, null);

            $row = array_combine($header, array_slice($values, 0, $columnCount));

            if (is_array($row)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}
