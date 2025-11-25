<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the series in this category
     */
    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Series::class, 'category_series');
    }
}
