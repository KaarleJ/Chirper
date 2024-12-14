<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChirpRequest;
use App\Http\Requests\UpdateChirpRequest;
use App\Services\ChirpService;
use App\Models\Chirp;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class ChirpController extends Controller
{
  protected ChirpService $chirpService;

  public function __construct(ChirpService $chirpService)
  {
    $this->chirpService = $chirpService;
  }

  /**
   * Display a listing of the resource.
   */
  public function index(): Response
  {
    $chirps = $this->chirpService->getAllChirpsWithLikes(Auth::id());
    return Inertia::render("Chirps/Index", ['chirps' => $chirps]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreChirpRequest $request)
  {
    $this->chirpService->createChirp($request->user(), $request->validated());
    return redirect()->route('chirps.index')->with('success', 'Chirp created successfully.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Chirp $chirp)
  {
    $chirpDetails = $this->chirpService->getChirpDetails($chirp, Auth::id());
    return Inertia::render("Chirps/Show", ['chirp' => $chirpDetails]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateChirpRequest $request, Chirp $chirp)
  {
    $this->chirpService->updateChirp($chirp, $request->validated());
    return redirect(route('chirps.index'));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Chirp $chirp)
  {
    $this->chirpService->deleteChirp($chirp);
    return redirect(route('chirps.index'));
  }
}
