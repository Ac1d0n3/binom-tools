<?php

namespace App\Http\Controllers\Search;

use App\Catalog\SearchIndex;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        private readonly SearchIndex $searchIndex,
    ) {}

    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $type = trim((string) $request->query('type', 'all'));
        if ($type === '') {
            $type = 'all';
        }

        $allowedTypes = array_merge(['all'], $this->searchIndex->types());
        if (! in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        $results = $query === ''
            ? []
            : $this->searchIndex->search($query, $type === 'all' ? null : $type);

        return view('search::index', [
            'query' => $query,
            'type' => $type,
            'types' => $this->searchIndex->types(),
            'results' => $results,
            'resultCount' => count($results),
        ]);
    }
}
