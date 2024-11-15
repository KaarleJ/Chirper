<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->input('query');
    $strategy = $request->input('strategy', 'people');
    $authUser = Auth::user();

    Log::info('Received strategy: ' . $strategy);

    $results = $strategy === 'people'
      ? User::where('name', 'ilike', "%{$query}%")
        ->withCount([
          'followers as is_following' => function ($query) use ($authUser) {
            $query->where('follower_id', $authUser->id);
          }
        ])
        ->get()
        ->map(function ($user) {
          $user->is_following = $user->is_following > 0;
          return $user;
        })
      : Chirp::where('message', 'ilike', "%{$query}%")->with('user:id,username,profile_picture')->get();


    return Inertia::render('Search', [
      'results' => $results,
      'query' => $query,
      'strategy' => $strategy,
    ]);
  }
}