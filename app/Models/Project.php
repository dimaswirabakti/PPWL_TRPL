<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'status', 'name', 'category_id', 'thumbnail'];

    /**
     * Local scope: hanya ambil proyek berstatus 'published'.
     * Pemakaian: Project::published()->get();
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}
