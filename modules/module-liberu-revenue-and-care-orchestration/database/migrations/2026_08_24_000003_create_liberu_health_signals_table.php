<?php

declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('liberu_health_signals', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('team_id')->index();
            $table->string('customer_id')->index();
            $table->string('kind');
            $table->string('status', 32)->index();
            $table->json('observation');
            $table->json('evidence')->nullable();
            $table->json('consent')->nullable();
            $table->timestamp('next_contact_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index(['team_id', 'customer_id', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liberu_health_signals');
    }
};
