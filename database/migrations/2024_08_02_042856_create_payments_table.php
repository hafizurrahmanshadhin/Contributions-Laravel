<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('collection_id');
            $table->foreign('collection_id')->references('id')->on('collections')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->string('name')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('transaction_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists('payments');
    }
};
