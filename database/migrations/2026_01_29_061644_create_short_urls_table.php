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
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id(); // auto-increment primary key (used for Base62)

            $table->text('original_url');

            $table->string('short_code', 10)
                ->nullable()
                ->unique();

            // unique index is enough for read-heavy redirects

            $table->unsignedBigInteger('clicks')->default(0);

            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_urls');
    }
};
