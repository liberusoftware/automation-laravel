<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('automation_automation_core_resources', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('team_id')->index();
            $table->string('name');
            $table->string('status', 32)->index();
            $table->json('payload')->nullable();
            $table->string('idempotency_key')->nullable();
            $table->timestamps();
            $table->unique(['team_id', 'idempotency_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_automation_core_resources');
    }
};
