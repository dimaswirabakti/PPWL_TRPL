<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = ['name'];

    /**
     * Project yang memakai skill ini (tabel pivot project_skill).
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
