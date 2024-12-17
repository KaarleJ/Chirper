<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Chat;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{

  protected $model = Chat::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    $userOne = User::factory()->create();
    $userTwo = User::factory()->create();

    return [
      'user_one_id' => $userOne->id,
      'user_two_id' => $userTwo->id,
    ];
  }
}
