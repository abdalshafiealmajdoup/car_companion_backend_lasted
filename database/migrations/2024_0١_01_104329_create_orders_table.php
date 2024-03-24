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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderID');
            $table->unsignedBigInteger('CustomerID');
            $table->unsignedBigInteger('CenterID');
            $table->unsignedBigInteger('ServiceID');
            $table->string('StatusOrder', 50);
            $table->string('CarType', 100)->nullable();
            $table->string('PhoneNumber', 20)->nullable();
            $table->string('GooglePlaceID', 255)->nullable();
            $table->string('Email', 255)->nullable();
            $table->text('CustomerNotes')->nullable();
            $table->text('City')->nullable();
            $table->text('Region')->nullable();
            $table->timestamps();

            $table->foreign('CustomerID')->references('CustomerID')->on('customers')->onDelete('cascade');
            $table->foreign('CenterID')->references('CenterID')->on('service_centers')->onDelete('cascade');
            $table->foreign('ServiceID')->references('ServiceID')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
