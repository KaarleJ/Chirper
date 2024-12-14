<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Services\LikeService;


class LikeController extends Controller
{
  protected LikeService $likeService;

  public function __construct(LikeService $likeService)
  {
    $this->likeService = $likeService;
  }
  /**
   * Toggle like on a chirp.
   */
  public function toggle(Chirp $chirp)
  {
    $likeDetails = $this->likeService->toggleLike($chirp);
    return response()->json($likeDetails);
  }
}
