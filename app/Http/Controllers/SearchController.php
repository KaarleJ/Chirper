<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->input('query');
    $strategy = $request->input('strategy', 'people');

    Log::info('Received strategy: ' . $strategy);

    $results = $strategy === 'people'
      ? User::where('name', 'ilike', "%{$query}%")->get()
      : Chirp::where('message', 'ilike', "%{$query}%")->with('user:id,username,profile_picture')->get();


    return Inertia::render('Search', [
      'results' => $results,
      'query' => $query,
      'strategy' => $strategy,
    ]);
  }
}