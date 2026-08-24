<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('automation_workflow_runs', function (Blueprint $table): void {
            $table->string('idempotency_key')->nullable()->after('failure_reason');
            $table->unique(['team_id', 'workflow_id', 'idempotency_key']);
        });
    }

    public function down(): void
    {
        Schema::table('automation_workflow_runs', function (Blueprint $table): void {
            $table->dropUnique(['team_id', 'workflow_id', 'idempotency_key']);
            $table->dropColumn('idempotency_key');
        });
    }
};
