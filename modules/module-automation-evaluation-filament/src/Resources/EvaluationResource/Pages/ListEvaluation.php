<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Filament\Resources\EvaluationResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\Evaluation\Filament\Resources\EvaluationResource;

final class ListEvaluation extends ListRecords
{
    protected static string $resource = EvaluationResource::class;
}
