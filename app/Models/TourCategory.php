<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class TourCategory extends Model
{
    use SoftDeletes;
    protected $fillable = ["name", "slug", "sort_order", "is_active"];
    protected function casts(): array
    {
        return ["is_active" => "boolean"];
    }
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, "category_id");
    }
}
