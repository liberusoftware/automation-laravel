<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Enums;

enum WorkflowStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Paused = 'paused';
    case Archived = 'archived';
}
