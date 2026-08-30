<?php

declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('automation_workflow_versions')) {
            Schema::table('automation_workflow_versions', function (Blueprint $table): void {
                $table->string('actor_id', 191)->nullable()->index();
                $table->string('correlation_id', 128)->nullable()->index();
                $table->json('metadata')->nullable();
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('automation_workflow_runs')) {
            Schema::table('automation_workflow_runs', function (Blueprint $table): void {
                $table->unsignedInteger('lock_version')->default(0);
                $table->unsignedInteger('attempts')->default(0);
                $table->boolean('cancel_requested')->default(false);
                $table->string('actor_id', 191)->nullable()->index();
                $table->string('correlation_id', 128)->nullable()->index();
                $table->json('metadata')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('finished_at')->nullable();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('automation_workflow_runs')) {
            Schema::table('automation_workflow_runs', function (Blueprint $table): void {
                $table->dropSoftDeletes();
                $table->dropColumn([
                    'lock_version',
                    'attempts',
                    'cancel_requested',
                    'actor_id',
                    'correlation_id',
                    'metadata',
                    'started_at',
                    'finished_at',
                ]);
            });
        }

        if (Schema::hasTable('automation_workflow_versions')) {
            Schema::table('automation_workflow_versions', function (Blueprint $table): void {
                $table->dropSoftDeletes();
                $table->dropColumn(['actor_id', 'correlation_id', 'metadata']);
            });
        }
    }
};
