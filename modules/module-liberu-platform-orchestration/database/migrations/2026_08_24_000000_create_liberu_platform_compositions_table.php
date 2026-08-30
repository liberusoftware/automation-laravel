<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('liberu_platform_compositions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('team_id')->index();
            $table->string('name');
            $table->string('status', 32)->index();
            $table->json('manifest');
            $table->json('capabilities')->nullable();
            $table->json('evidence')->nullable();
            $table->string('idempotency_key')->nullable();
            $table->timestamps();
            $table->unique(['team_id', 'idempotency_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liberu_platform_compositions');
    }
};
