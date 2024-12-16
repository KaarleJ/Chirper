<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'username' => 'required|string|alpha_dash|max:255|unique:users,username',
      'email' => 'required|string|lowercase|email|max:255|unique:users,email',
      'password' => ['required', 'confirmed', Password::defaults()],
    ];
  }
}
