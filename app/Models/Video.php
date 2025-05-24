<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'vimeo_uri',
        'vimeo_link',
        'embed_html',
        'trailer_path',     // Corrected from trailer_link to trailer_path
        'thumbnail_path',
        'cast',
        'user_id',
    ];

    /**
     * Casts attributes to native types.
     * Since `cast` is a string but you want to handle it as array, cast it as array.
     */
    protected $casts = [
        'cast' => 'array',
    ];

    // Define relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
