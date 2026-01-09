<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'path',
        'size',
        'mime_type',
        'user_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Upload::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Upload::class, 'parent_id');
    }

}
