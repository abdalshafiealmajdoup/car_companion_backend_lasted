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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('AddressID');
            $table->unsignedBigInteger('CustomerID')->nullable();
            $table->unsignedBigInteger('CenterID')->nullable();
            $table->string('Country', 100);
            $table->string('City', 100);
            $table->string('District', 100);
            $table->string('Street', 255);
            $table->string('Building', 50);
            $table->string('ZipCode', 20);
            $table->decimal('Latitude', 10, 8);
            $table->decimal('Longitude', 11, 8);
            $table->text('AdditionalInfo')->nullable();
            $table->timestamps();

            $table->foreign('CustomerID')->references('CustomerID')->on('customers')->onDelete('set null');
            $table->foreign('CenterID')->references('CenterID')->on('service_centers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
