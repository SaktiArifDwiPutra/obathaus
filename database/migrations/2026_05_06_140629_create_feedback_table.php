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
    Schema::create('feedbacks', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('whatsapp_number');
        $table->integer('rating'); // 1 sampai 5
        $table->text('message'); // Kritik / Saran
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
