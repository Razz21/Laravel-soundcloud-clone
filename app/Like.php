<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use SoftDeletes;

    protected $table = 'likeables';
    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    public function tracks()
    {
        return $this->morphedByMany(Track::class, 'likeable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likeable()
    {
        return $this->morphTo('likeable');
    }
}
