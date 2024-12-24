<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    protected $fillable = ['coordinates', 'title', 'color'];

    // Vous pouvez ajouter d'autres propriétés si nécessaire

    // Si vous n'avez pas besoin de timestamps (created_at, updated_at)
    public $timestamps = false;
}
