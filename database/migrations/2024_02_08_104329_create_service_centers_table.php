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
        Schema::create('service_centers', function (Blueprint $table) {
            $table->id('CenterID');
            $table->string('Name', 255);
            $table->string('Phone', 20)->unique();
            $table->string('Email', 255)->unique();
            $table->json('ServicesOffered');
            $table->json('CarTypesServiced');
            $table->text('City')->nullable();
            $table->text('Region')->nullable();
            $table->string('Password', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_centers');
    }
};
