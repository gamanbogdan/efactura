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
        Schema::create('efactura_invoice_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->unsigned(); 
            $table->string('Nota', 1000)->nullable();
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('efactura_invoice')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_comments');
    }
};
