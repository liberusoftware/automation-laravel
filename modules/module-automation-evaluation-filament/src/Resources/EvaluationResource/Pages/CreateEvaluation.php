<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Filament\Resources\EvaluationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\Evaluation\Filament\Resources\EvaluationResource;

final class CreateEvaluation extends CreateRecord
{
    protected static string $resource = EvaluationResource::class;
}
