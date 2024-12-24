<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForeignTableProigeo extends Model
{
    use HasFactory;
     // Specify the foreign keys
     protected $primaryKey = 'id';
     public $incrementing = true;
     protected $table = 'foreign_table_proigeo';
     protected $fillable = ['proi_id', 'polygons_id','disponibility'];

     // Define the relationships
     public function proi()
     {
         return $this->belongsTo(Proi::class, 'proi_id');
     }

     public function polygon()
     {
         return $this->belongsTo(Polygon::class, 'polygons_id');
     }
 }



