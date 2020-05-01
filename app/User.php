<?php

namespace App;

use App\Profile;
use App\Track;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use InteractsWithMedia, Notifiable;

    public static $defaultImage = '/avatars/default.jpg';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', "url", "screen_name",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'email_verified_at',
        "media",
        'avatar',
    ];

    protected $with = [];

    protected $appends = ['thumbnail', "avatar"];
    protected $touches = ['subscribed', 'subscribers', 'history'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return "url";
    }

    public function subscribedBy(int $user_id)
    {
        return $this->subscribers->contains($user_id);
    }

    public function getIsSubscribedAttribute()
    {
        return Auth::check() && $this->subscribedBy(Auth::id());
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function subscribed()
    {
        return $this->belongsToMany(static::class, 'subscriptions', 'user_id', 'target_id')
            ->withTimestamps()
            ->withPivot('id')
            ->latest();
    }

    public function subscribers()
    {
        return $this->belongsToMany(static::class, 'subscriptions', 'target_id', 'user_id')
            ->withTimestamps()
            ->withPivot(['id'])
            ->latest();
    }
    /**
     * Return subscribed users tracks
     *
     * @return mixed
     */
    public function getFeed()
    {
        $userIds = $this->subscribed->pluck('id');
        return \Track::whereIn('user_id', $userIds)->latest();
    }

    public function tracks()
    {
        return $this->hasMany(Track::class)->latest();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function likedTracks()
    {
        return $this->morphedByMany(Track::class, 'likeable')->whereDeletedAt(null)->latest();
    }

    public function history()
    {
        return $this->belongsToMany(Track::class, 'histories')->withTimestamps()->latest();
    }

    /* =================== media-library =================== */
    public function thumbnail()
    {
        if ($this->media->first()) {

            return $this->media->first()->getFullUrl('small');
        }
        // return default image path
        return $this->getFirstMediaUrl('avatars');
    }
    public function getThumbnailAttribute()
    {
        return $this->thumbnail();
    }
    public function getAvatarAttribute()
    {
        return $this->avatar();
    }
    public function avatar()
    {
        if ($this->media->first()) {

            return $this->media->first()->getFullUrl('big');
        }
        // return default image path
        return $this->getFirstMediaUrl('avatars');

    }

    public function images($collectionName)
    {
        $conversions = ['avatar', 'thumb'];
        $images = $this->getFirstMedia($collectionName);
        $result = array();
        if (!$images) {
            $default = $this->getFirstMediaUrl($collectionName);
            foreach ($conversions as $conv) {
                $result[$conv] = $default;
            }
        } else {
            foreach ($conversions as $conv) {
                $result[$conv] = $images->getUrl($conv);
            }
        }
        // return same array
        return $result;
    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 75, 75)
            ->performOnCollections('avatars');

        $this->addMediaConversion('big')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('avatars');

    }
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatars')
            ->useDisk('avatars')
            ->singleFile()
            ->useFallbackUrl(\Storage::disk('public')->url(self::$defaultImage))
            ->useFallbackPath(public_path(self::$defaultImage));
    }

}
