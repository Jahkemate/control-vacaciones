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
        Schema::create('balance_vacation', function (Blueprint $table) {
            $table->id();
            $table->integer('accrued_total');
            $table->integer('accrued_this_year');
            $table->integer('used');
            $table->integer('balance');
            $table->integer('pendings')->nullable();
            $table->string('notes')->nullable();
            $table->integer('employee_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_vacation');
    }
};
