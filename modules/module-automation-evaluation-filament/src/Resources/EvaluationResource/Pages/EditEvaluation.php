<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Filament\Resources\EvaluationResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Liberu\Modules\Automation\Evaluation\Filament\Resources\EvaluationResource;

final class EditEvaluation extends EditRecord
{
    protected static string $resource = EvaluationResource::class;
}
