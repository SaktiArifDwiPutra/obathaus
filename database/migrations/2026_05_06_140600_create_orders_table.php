<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('whatsapp_number');
        $table->string('latitude'); // Titik koordinat Map
        $table->string('longitude');
        $table->text('address_detail'); // Alamat tertulis (Patokan, No Rumah)
        $table->integer('total_price');
        $table->string('status')->default('pending'); // pending, on_delivery, completed
        $table->timestamps();
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
