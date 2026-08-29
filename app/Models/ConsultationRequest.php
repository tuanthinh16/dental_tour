<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class ConsultationRequest extends Model
{
    use SoftDeletes;
    public const STATUSES = ["new", "contacted", "completed", "cancelled"];
    protected $fillable = [
        "full_name",
        "email",
        "phone",
        "country",
        "tour_id",
        "travel_date",
        "number_of_people",
        "message",
        "status",
        "utm_source",
        "utm_medium",
        "utm_campaign",
    ];
    protected function casts(): array
    {
        return ["travel_date" => "date"];
    }
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
