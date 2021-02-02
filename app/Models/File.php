<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name', 'path', 'type', 'size', 'category'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'file_users');
    }
}
