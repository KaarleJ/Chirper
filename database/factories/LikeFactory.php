<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
  protected $model = Like::class;

  public function definition()
  {
    return [
      'user_id' => User::factory(),
      'chirp_id' => Chirp::factory(),
    ];
  }
}
