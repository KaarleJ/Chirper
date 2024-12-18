<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Chirp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
  protected $model = Comment::class;

  public function definition()
  {
    return [
      'user_id' => User::factory(),
      'chirp_id' => Chirp::factory(),
      'content' => $this->faker->sentence,
    ];
  }
}
