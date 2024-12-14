<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use App\Http\Requests\SearchRequest;
use Inertia\Inertia;

class SearchController extends Controller
{
  protected SearchService $searchService;

  public function __construct(SearchService $searchService)
  {
    $this->searchService = $searchService;
  }

  /**
   * Handle search queries.
   */
  public function index(SearchRequest $request)
  {
    $query = $request->input('query');
    $strategy = $request->input('strategy', 'people');

    $results = $this->searchService->search($query, $strategy);

    return Inertia::render('Search', [
      'results' => $results,
      'strategy' => $strategy,
    ]);
  }

  public function search(SearchRequest $request)
  {
    $query = $request->input('query');
    $strategy = $request->input('strategy', 'people');

    $results = $this->searchService->search($query, $strategy);

    return response()->json($results);
  }
}