<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ForeignTableProigeo;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foreign_table_proigeo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proi_id')->constrained('proi');
            $table->foreignId('polygons_id')->constrained('polygons');
            $table->boolean('disponibility')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foreign_table_proigeo');
    }
};
