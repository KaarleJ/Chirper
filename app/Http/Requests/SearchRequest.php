<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'query' => 'nullable|string|max:255',
      'strategy' => 'nullable|string|in:people,chirps',
    ];
  }
}
