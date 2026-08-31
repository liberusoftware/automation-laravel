<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamOnboarding extends Model
{
    protected $table = 'team_onboarding';

    protected $fillable = ['team_id', 'completed_at'];

    protected $casts = ['completed_at' => 'datetime'];
}
