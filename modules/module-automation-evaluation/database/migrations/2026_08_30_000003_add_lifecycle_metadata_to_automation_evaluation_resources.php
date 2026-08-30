<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    private string $table = 'automation_evaluation_resources';

    public function up(): void
    {
        if (! Schema::hasTable($this->table)) {
            return;
        }

        Schema::table($this->table, function (Blueprint $table): void {
            $table->unsignedInteger('lock_version')->default(0);
            $table->string('actor_id', 191)->nullable()->index();
            $table->string('correlation_id', 128)->nullable()->index();
            $table->json('metadata')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable($this->table)) {
            return;
        }

        Schema::table($this->table, function (Blueprint $table): void {
            $table->dropSoftDeletes();
            $table->dropColumn(['lock_version', 'actor_id', 'correlation_id', 'metadata']);
        });
    }
};
