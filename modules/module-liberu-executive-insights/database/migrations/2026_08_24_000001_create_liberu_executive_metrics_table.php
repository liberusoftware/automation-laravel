<?php

declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('liberu_executive_metrics', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('team_id')->index();
            $table->string('key');
            $table->string('status', 32)->index();
            $table->json('definition');
            $table->json('lineage')->nullable();
            $table->json('snapshot')->nullable();
            $table->timestamp('fresh_at')->nullable();
            $table->timestamps();
            $table->unique(['team_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liberu_executive_metrics');
    }
};
