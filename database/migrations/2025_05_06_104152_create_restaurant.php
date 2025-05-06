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
        Schema::create('restaurants', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->timestamps();
        });

        Schema::create('halls', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tables', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('seats');
            $table->foreignId('hall')->constrained('halls');
            $table->timestamps();
        });

        Schema::create('orders', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user')->constrained('users');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'finished']);
            $table->morphs('entity');
            $table->timestamps();

            $table->index(['entity_id', 'entity_type'], 'user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('halls');
        Schema::dropIfExists('restaurant');
    }
};
