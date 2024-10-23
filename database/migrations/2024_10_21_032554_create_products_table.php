<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('ingredients');
            $table->date('production_date');
            $table->date('expire_date')->nullable();
            $table->foreignId('location_id')->constrained('locations', 'id')->cascadeOnDelete();
            $table->foreignId('subLocation_id')->nullable()->constrained('sub_locations', 'id')->cascadeOnDelete();
            $table->integer('qty');
            $table->integer('alert_qty')->nullable();
            $table->string('qr_code')->nullable()->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
