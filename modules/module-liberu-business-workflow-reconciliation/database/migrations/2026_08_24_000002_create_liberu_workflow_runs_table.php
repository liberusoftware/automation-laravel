<?php

declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('liberu_workflow_runs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('team_id')->index();
            $table->string('workflow');
            $table->string('correlation_id');
            $table->string('status', 32)->index();
            $table->json('steps')->nullable();
            $table->json('events')->nullable();
            $table->json('recovery')->nullable();
            $table->timestamps();
            $table->unique(['team_id', 'correlation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liberu_workflow_runs');
    }
};
