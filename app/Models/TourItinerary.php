<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class TourItinerary extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "tour_id",
        "day_number",
        "title",
        "description",
        "sort_order",
        "is_active",
    ];
    protected function casts(): array
    {
        return ["is_active" => "boolean"];
    }
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
