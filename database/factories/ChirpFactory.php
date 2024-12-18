<?php

namespace Database\Factories;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChirpFactory extends Factory
{
  protected $model = Chirp::class;

  public function definition()
  {
    return [
      'user_id' => User::factory(),
      'message' => $this->faker->sentence,
    ];
  }
}
