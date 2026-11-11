<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    protected $fillable = ['project_id', 'image', 'caption', 'sort_order'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * URL publik gambar galeri, null jika path kosong. Pemakaian: $image->url
     */
    protected function url(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->image ? asset('storage/'.$this->image) : null);
    }
}
