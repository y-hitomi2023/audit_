<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'file',
        'article_id',
        // 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
