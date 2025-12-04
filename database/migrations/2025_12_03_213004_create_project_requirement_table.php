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
        Schema::create('project_requirement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('requirement_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_requirement', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['requirement_id']);
            $table->dropColumn('project_id');
            $table->dropColumn('requirement_id');
        });
        Schema::dropIfExists('project_requirement');
    }
};
