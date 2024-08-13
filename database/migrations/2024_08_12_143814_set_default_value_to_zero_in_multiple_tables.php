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
        Schema::table('interest_rates', function (Blueprint $table) {
            $table->decimal('interest_rate', 5, 2)->default(0)->change();
        });

        Schema::table('investments', function (Blueprint $table) {
            $table->decimal('interest_rate', 5, 2)->default(0)->change();
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->change();
            $table->integer('duration')->default(0)->change();
            $table->decimal('yearly_interest_rate', 5, 2)->default(0)->change();
            $table->decimal('loan_interest_rate', 5, 2)->default(0)->change();
            $table->decimal('weekly_interest_rate', 5, 2)->default(0)->change();
            $table->tinyInteger('status')->default(1)->change();
        });

        Schema::table('loan_collections', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->change();
        });

        Schema::table('loan_installments', function (Blueprint $table) {
            $table->decimal('capital', 15, 2)->default(0)->change();
            $table->decimal('interest', 15, 2)->default(0)->change();
            $table->decimal('payment', 15, 2)->default(0)->change();
            $table->decimal('balance', 15, 2)->default(0)->change();
        });

        Schema::table('loan_requests', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->change();
            $table->decimal('interest_rate', 5, 2)->default(0)->change();
            $table->integer('duration')->default(0)->change();
            $table->tinyInteger('status')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interest_rates', function (Blueprint $table) {
            $table->decimal('interest_rate', 5, 2)->nullable()->change();
        });

        Schema::table('interest_rates', function (Blueprint $table) {
            $table->decimal('interest_rate', 5, 2)->default(0)->change();
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->change();
            $table->integer('duration')->nullable()->change();
            $table->decimal('yearly_interest_rate', 5, 2)->nullable()->change();
            $table->decimal('loan_interest_rate', 5, 2)->nullable()->change();
            $table->decimal('weekly_interest_rate', 5, 2)->nullable()->change();
            $table->tinyInteger('status')->nullable()->change();
        });

        Schema::table('loan_collections', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->change();
        });

        Schema::table('loan_installments', function (Blueprint $table) {
            $table->decimal('capital', 15, 2)->nullable()->change();
            $table->decimal('interest', 15, 2)->nullable()->change();
            $table->decimal('payment', 15, 2)->nullable()->change();
            $table->decimal('balance', 15, 2)->nullable()->change();
        });

        Schema::table('loan_requests', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->change();
            $table->decimal('interest_rate', 5, 2)->nullable()->change();
            $table->integer('duration')->nullable()->change();
            $table->tinyInteger('status')->nullable()->change();
        });
    }
};
