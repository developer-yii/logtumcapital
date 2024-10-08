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
        Schema::create('loan_collections', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable();
            $table->integer('collector_id')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('bank_receipt')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_collections');
    }
};
