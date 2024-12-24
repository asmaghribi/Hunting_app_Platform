<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proi extends Model
{
    use HasFactory;
    protected $table = 'proi';
    
    protected $fillable = ['name','image', 'species', 'type'];
}
