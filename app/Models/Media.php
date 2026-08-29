<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Media extends Model
{
    use SoftDeletes;
    protected $fillable = ["file_name", "file_path", "alt_text", "is_active"];
    protected function casts(): array
    {
        return ["is_active" => "boolean"];
    }
}
