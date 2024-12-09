<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ChirpController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): Response
  {
    return Inertia::render("Chirps/Index", [
      'chirps' => Chirp::with('user:id,username,profile_picture,name')
        ->withCount('likes')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($chirp) {
          $chirp->setAttribute('liked', $chirp->likes()->where('user_id', Auth::user()->id)->exists());
          return $chirp;
        })
    ]);

  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'message' => 'required|string|max:255',
    ]);

    $request->user()->chirps()->create($validated);

    return redirect(route('chirps.index'));
  }

  /**
   * Display the specified resource.
   */
  public function show(Chirp $chirp)
  {
    $chirp->loadCount('likes');
    $chirp->setAttribute('liked', $chirp->likes()->where('user_id', Auth::user()->id)->exists());
    return Inertia::render("Chirps/Show", ['chirp' => $chirp->load(relations: ['user:id,username,profile_picture,name', 'comments.user:id,username,profile_picture,name'])]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Chirp $chirp)
  {
    Gate::authorize('update', $chirp);

    $validated = $request->validate([
      'message' => 'required|string|max:255',
    ]);

    $chirp->update($validated);

    return redirect(route('chirps.index'));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Chirp $chirp)
  {
    Gate::authorize('delete', $chirp);

    $chirp->delete();

    return redirect(route('chirps.index'));
  }
}
