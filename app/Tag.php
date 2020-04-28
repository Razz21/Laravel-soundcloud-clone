<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function tracks()
    {
        return $this->morphedByMany(Track::class, 'taggable');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            // TODO remove all related taggables
            DB::table('taggables')->where('tag_id', $model->id)->delete();
        });
    }

}
