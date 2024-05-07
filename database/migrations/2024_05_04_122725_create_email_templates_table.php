<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_key', 50)->nullable(false)->unique();
            $table->string('subject', 100)->nullable(false);
            $table->longText('content')->nullable(false);
            $table->longText('email_variables')->nullable(true);
            $table->tinyInteger('status')->default(0)->comment('0-Active,1-Inactive');
            $table->tinyInteger('is_deleted')->default(0)->comment('0-Notdeleted,1-deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
