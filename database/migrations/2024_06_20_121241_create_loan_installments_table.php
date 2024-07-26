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
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('loan_id')->nullable();
            $table->integer('loan_collection_id')->nullable();
            $table->date('installment_date')->nullable();
            $table->decimal('capital', 15, 2)->nullable();
            $table->decimal('interest', 15, 2)->nullable();
            $table->decimal('payment', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->nullable();
            $table->tinyInteger('status')->comment('1 => Pending, 2 => Paid')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_installments');
    }
};
