<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
  use HasFactory;

  protected $fillable = ['chirp_id', 'user_id'];

  public function chirp()
  {
    return $this->belongsTo(Chirp::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
