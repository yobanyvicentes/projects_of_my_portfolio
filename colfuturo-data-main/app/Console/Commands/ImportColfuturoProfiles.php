<?php

namespace App\Console\Commands;

use App\Models\ColfuturoProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportColfuturoProfiles extends Command
{
    protected $signature = 'academic-insights:import
                            {path : Relative or absolute path to the COLFUTURO CSV file}
                            {--truncate : Clear the table before importing}';

    protected $description = 'Import COLFUTURO selected profiles from a CSV file';

    public function handle(): int
    {
        $path = $this->resolvePath($this->argument('path'));

        if (! is_file($path)) {
            $this->error('The provided CSV path does not exist: ' . $path);
            return self::FAILURE;
        }

        if ($this->option('truncate')) {
            ColfuturoProfile::truncate();
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            $this->error('Unable to open the CSV file.');
            return self::FAILURE;
        }

        try {
            $headers = fgetcsv($handle);

            if (! is_array($headers)) {
                $this->error('The CSV file does not contain a valid header row.');
                return self::FAILURE;
            }

            $headers = $this->normalizeHeaders($headers);

            $count = 0;
            $skipped = 0;

            DB::transaction(function () use ($handle, $headers, &$count, &$skipped) {
                while (($row = fgetcsv($handle)) !== false) {
                    if (! is_array($row)) {
                        $skipped++;
                        continue;
                    }

                    if (count($row) !== count($headers)) {
                        $skipped++;
                        continue;
                    }

                    $data = array_combine($headers, $row);

                    if (! is_array($data)) {
                        $skipped++;
                        continue;
                    }

                    $payload = [
                        'promotion_year' => $this->normalizeYear($data['prom'] ?? null),
                        'name' => trim((string) ($data['nombre'] ?? '')),
                        'gender' => $this->nullable($data['genero'] ?? null),
                        'department' => $this->nullable($data['departamento'] ?? null),
                        'undergraduate_university' => $this->nullable($data['univ_pregrado'] ?? null),
                        'undergraduate_program' => $this->nullable($data['pregrado'] ?? null),
                        'postgraduate_university' => $this->nullable($data['univ_posgrado'] ?? null),
                        'country' => $this->nullable($data['pais'] ?? null),
                        'destination_city' => $this->nullable($data['ciudad_destino'] ?? null),
                        'postgraduate_type' => $this->nullable($data['tipo'] ?? null),
                        'postgraduate_program' => $this->nullable($data['posgrado'] ?? null),
                        'area' => $this->nullable($data['area'] ?? null),
                        'status' => $this->nullable($data['estado'] ?? null),
                    ];

                    $payload['search_vector'] = collect($payload)
                        ->except(['promotion_year'])
                        ->filter()
                        ->implode(' | ');

                    if ($payload['name'] === '') {
                        $skipped++;
                        continue;
                    }

                    ColfuturoProfile::create($payload);
                    $count++;
                }
            });

            $this->info("Imported {$count} COLFUTURO profiles successfully.");

            if ($skipped > 0) {
                $this->warn("Skipped {$skipped} rows due to invalid structure or missing required data.");
            }

            return self::SUCCESS;
        } finally {
            fclose($handle);
        }
    }

    private function resolvePath(string $path): string
    {
        $path = trim($path);

        if ($this->isAbsolutePath($path)) {
            return $path;
        }

        return base_path($path);
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/')
            || str_starts_with($path, '\\')
            || preg_match('/^[A-Za-z]:\\\\/', $path) === 1;
    }

    private function normalizeHeaders(array $headers): array
    {
        return array_map(function ($header) {
            $header = (string) $header;

            // Remove UTF-8 BOM at the beginning
            $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);

            // Remove invisible Unicode spaces / marks
            $header = preg_replace('/[\x{00A0}\x{200B}\x{200C}\x{200D}\x{FEFF}]/u', '', $header);

            $header = trim($header);

            return (string) Str::of($header)
                ->ascii()
                ->lower()
                ->replace('.', '')
                ->replaceMatches('/\s+/', '_')
                ->replaceMatches('/[^a-z0-9_]/', '');
        }, $headers);
    }

    private function nullable($value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function normalizeYear($value): ?int
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (preg_match('/\b(19|20)\d{2}\b/', $value, $matches)) {
            return (int) $matches[0];
        }

        return is_numeric($value) ? (int) $value : null;
    }
}
