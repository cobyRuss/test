<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('service_photos')) {
            Schema::create('service_photos', function (Blueprint $table) {
                $table->id();
                $table->enum('category', ['weddings', 'events', 'corporate', 'sympathy', 'romance', 'getwell']);
                $table->string('image_url');
                $table->string('caption')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_photos');
    }
};
