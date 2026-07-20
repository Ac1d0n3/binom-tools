<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BiPythonToolkitController extends Controller
{
    private const DOWNLOADS = [
        'bi-kpi-export' => [
            'path' => 'scripts/bi_kpi_export.py',
            'name' => 'bi_kpi_export.py',
            'type' => 'text/x-python',
        ],
        'qlik-app-inventory' => [
            'path' => 'scripts/qlik_app_inventory.py',
            'name' => 'qlik_app_inventory.py',
            'type' => 'text/x-python',
        ],
        'readme' => [
            'path' => 'docs/bi-kpi-export.md',
            'name' => 'BI_EXPORT_TOOLKIT.md',
            'type' => 'text/markdown',
        ],
    ];

    public function show(): View
    {
        return view('tools.bi-python-toolkit.show', [
            'scripts' => collect(self::DOWNLOADS)
                ->reject(fn (array $entry, string $key): bool => $key === 'readme')
                ->map(fn (array $entry, string $key): array => [
                    'id' => $key,
                    'name' => $entry['name'],
                    'download' => $key,
                    'content' => File::isFile(base_path($entry['path']))
                        ? File::get(base_path($entry['path']))
                        : '',
                ])
                ->values()
                ->all(),
        ]);
    }

    public function download(string $file): BinaryFileResponse|Response
    {
        $entry = self::DOWNLOADS[$file] ?? null;

        if ($entry === null) {
            abort(404);
        }

        $path = base_path($entry['path']);

        if (! File::isFile($path)) {
            abort(404);
        }

        return response()->download($path, $entry['name'], [
            'Content-Type' => $entry['type'],
        ]);
    }
}
