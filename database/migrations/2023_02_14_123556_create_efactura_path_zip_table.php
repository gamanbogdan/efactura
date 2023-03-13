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
        Schema::create('efactura_path_zip', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();            
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('number_invoices')->unsigned()->nullable(); 
            $table->timestamps();
        });

        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_path_zip');
    }
};