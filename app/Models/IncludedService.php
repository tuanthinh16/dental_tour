<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncludedService extends Model
{
    use SoftDeletes;

    protected $table = 'tour_services';

    protected $fillable = ['name', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(
            Tour::class,
            'tour_service_assignments',
            'tour_service_id',
            'tour_id',
        )->withTimestamps();
    }
}
