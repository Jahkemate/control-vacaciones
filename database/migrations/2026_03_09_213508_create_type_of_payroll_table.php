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
        Schema::create('type_of_payroll', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_type', 100)->nullable();
            $table->string('vacations_days', 100)->nullable();
            $table->string('vacations_bonus', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_of_payroll');
    }
};
