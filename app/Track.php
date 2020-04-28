<?php

namespace App;

use App\Helpers\Likeable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Track extends Model implements HasMedia
{
    use InteractsWithMedia, Likeable;

    protected $guarded = []; //todo fillable
    protected $casts = [
        'wave' => 'array',
        'created_at' => 'datetime',
    ];
    protected $with = ['genre', 'tags'];

    public function getUrlAttribute($value)
    {
        return \Storage::disk('public')->url($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function delete()
    {
        $this->tags()->detach();
        Storage::disk('public')->deleteDirectory("files/" . $this->id); // stream files
        return parent::delete();
    }

    public function thumbnail()
    {
        if ($this->media->first()) {

            return $this->media->first()->getFullUrl('small');
        }
        return null;
    }
    public function cover()
    {
        if ($this->media->first()) {

            return $this->media->first()->getFullUrl('big');
        }
        return null;
    }

    public function getThumbnailAttribute()
    {
        return $this->thumbnail();
    }
    public function getCoverAttribute()
    {
        return $this->cover();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('covers')
            ->useDisk('covers')
            ->singleFile();
    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->performOnCollections('covers');

        $this->addMediaConversion('big')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('covers');
    }
}
