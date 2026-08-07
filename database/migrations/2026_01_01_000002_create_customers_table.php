<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('full_name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('municipality')->nullable();
                $table->text('address')->nullable();
                $table->string('password_hash');
                $table->string('reset_token')->nullable();
                $table->timestamp('reset_expires')->nullable();
                $table->string('reset_code')->nullable();
                $table->timestamp('reset_code_expires')->nullable();
                $table->unsignedTinyInteger('reset_code_attempts')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
