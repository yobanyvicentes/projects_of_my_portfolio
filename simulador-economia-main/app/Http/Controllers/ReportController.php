<?php

namespace App\Http\Controllers;

use App\Models\SimulationRun;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $runs = SimulationRun::query()
            ->where('user_id', auth()->id())
            ->with(['scenario', 'results'])
            ->latest()
            ->paginate(10);

        return view('reports.index', compact('runs'));
    }

    public function json(SimulationRun $run): Response
    {
        $this->authorizeRun($run);

        $run->load(['scenario', 'results' => fn ($query) => $query->orderBy('period')]);

        $payload = [
            'run' => [
                'id' => $run->id,
                'status' => $run->status,
                'seed' => $run->seed,
                'executed_at' => optional($run->executed_at)->toDateTimeString(),
                'notes' => $run->notes,
            ],
            'scenario' => [
                'id' => $run->scenario->id,
                'name' => $run->scenario->name,
                'market_type' => $run->scenario->market_type,
                'competitive_strategy' => $run->scenario->competitive_strategy,
                'company_a_price' => $run->scenario->company_a_price,
                'company_b_price' => $run->scenario->company_b_price,
                'company_a_ad_budget' => $run->scenario->company_a_ad_budget,
                'company_b_ad_budget' => $run->scenario->company_b_ad_budget,
                'consumers_count' => $run->scenario->consumers_count,
                'periods_count' => $run->scenario->periods_count,
            ],
            'results' => $run->results->map(fn ($result) => [
                'period' => $result->period,
                'company_a_sales' => $result->company_a_sales,
                'company_b_sales' => $result->company_b_sales,
                'company_a_market_share' => $result->company_a_market_share,
                'company_b_market_share' => $result->company_b_market_share,
                'company_a_profit' => $result->company_a_profit,
                'company_b_profit' => $result->company_b_profit,
                'hhi' => $result->hhi,
                'leader_company' => $result->leader_company,
                'raw_data' => $result->raw_data,
            ]),
        ];

        return response(
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            200,
            [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="simulation-run-' . $run->id . '.json"',
            ]
        );
    }

    public function csv(SimulationRun $run): StreamedResponse
    {
        $this->authorizeRun($run);

        $run->load(['scenario', 'results' => fn ($query) => $query->orderBy('period')]);

        $filename = 'simulation-run-' . $run->id . '.csv';

        return response()->streamDownload(function () use ($run) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'period',
                'company_a_sales',
                'company_b_sales',
                'company_a_market_share',
                'company_b_market_share',
                'company_a_profit',
                'company_b_profit',
                'hhi',
                'leader_company',
            ]);

            foreach ($run->results as $result) {
                fputcsv($handle, [
                    $result->period,
                    $result->company_a_sales,
                    $result->company_b_sales,
                    $result->company_a_market_share,
                    $result->company_b_market_share,
                    $result->company_a_profit,
                    $result->company_b_profit,
                    $result->hhi,
                    $result->leader_company,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function pdf(SimulationRun $run): Response
    {
        $this->authorizeRun($run);

        $run->load(['scenario', 'results' => fn ($query) => $query->orderBy('period')]);

        $finalResult = $run->results->last();

        $lines = [
            'Economy Simulator Report',
            'Run ID: ' . $run->id,
            'Scenario: ' . $run->scenario->name,
            'Market Type: ' . Str::title(str_replace('_', ' ', $run->scenario->market_type)),
            'Strategy: ' . Str::title(str_replace('_', ' ', $run->scenario->competitive_strategy)),
            'Seed: ' . $run->seed,
            'Executed At: ' . optional($run->executed_at)->format('Y-m-d H:i:s'),
            ' ',
            'Final Results',
            'Company A Sales: ' . ($finalResult->company_a_sales ?? 0),
            'Company B Sales: ' . ($finalResult->company_b_sales ?? 0),
            'Company A Market Share: ' . number_format((float) ($finalResult->company_a_market_share ?? 0) * 100, 2) . '%',
            'Company B Market Share: ' . number_format((float) ($finalResult->company_b_market_share ?? 0) * 100, 2) . '%',
            'Company A Profit: ' . number_format((float) ($finalResult->company_a_profit ?? 0), 2),
            'Company B Profit: ' . number_format((float) ($finalResult->company_b_profit ?? 0), 2),
            'HHI: ' . number_format((float) ($finalResult->hhi ?? 0), 4),
            'Leader: ' . ($finalResult->leader_company ?? 'N/A'),
        ];

        $pdf = $this->buildSimplePdf($lines);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="simulation-run-' . $run->id . '.pdf"',
        ]);
    }

    protected function authorizeRun(SimulationRun $run): void
    {
        abort_unless($run->user_id === auth()->id(), 403);
    }

    protected function buildSimplePdf(array $lines): string
    {
        $content = "BT\n/F1 16 Tf\n50 770 Td\n";

        foreach ($lines as $index => $line) {
            $fontSize = $index === 0 ? 16 : 11;
            $content .= "/F1 {$fontSize} Tf\n";
            $content .= '(' . $this->escapePdfText($line) . ") Tj\n";
            $content .= "0 -18 Td\n";
        }

        $content .= "ET";
        $length = strlen($content);

        $objects = [];
        $objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $objects[] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        $objects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n";
        $objects[] = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
        $objects[] = "5 0 obj\n<< /Length {$length} >>\nstream\n{$content}\nendstream\nendobj\n";

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xrefPosition = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }

        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefPosition}\n%%EOF";

        return $pdf;
    }

    protected function escapePdfText(string $value): string
    {
        return str_replace(
            ['\\', '(', ')'],
            ['\\\\', '\\(', '\\)'],
            $value
        );
    }
}
