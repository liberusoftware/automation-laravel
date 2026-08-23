<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('automation_workflow_versions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id')->index();
            $table->uuid('team_id')->index();
            $table->unsignedInteger('version');
            $table->json('definition');
            $table->timestamps();
            $table->unique(['workflow_id', 'version']);
        });

        Schema::create('automation_workflow_runs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id')->index();
            $table->uuid('team_id')->index();
            $table->unsignedInteger('version');
            $table->string('status', 32)->index();
            $table->json('variables')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_workflow_runs');
        Schema::dropIfExists('automation_workflow_versions');
    }
};
