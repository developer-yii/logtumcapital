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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('company_admin_id')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->integer('duration')->nullable();
            $table->decimal('yearly_interest_rate', 5, 2)->nullable();
            $table->decimal('loan_interest_rate', 5, 2)->nullable();
            $table->decimal('weekly_interest_rate', 5, 2)->nullable();
            $table->date('first_installment_date')->nullable();
            $table->date('last_installment_date')->nullable();
            $table->tinyInteger('status')->comment('1 => pending, 2 => approved, 3 => partial disbursed, 4 => disbursed, 5 => completed, 6 => defaulted, 7 => rejected');
            $table->string('ioweyou')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
