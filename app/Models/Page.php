<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Page extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "title",
        "slug",
        "content",
        "seo_title",
        "seo_description",
        "is_active",
    ];
    protected function casts(): array
    {
        return ["is_active" => "boolean"];
    }
}
