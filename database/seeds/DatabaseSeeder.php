<?php

use App\Comment;
use App\Genre;
use App\Profile;
use App\Tag;
use App\Track;
use App\User;
use Illuminate\Database\Seeder;

function imageUrl($width = 640, $height = 480)
{
    $randomize_number = rand(1, 100);

    $baseUrl = "https://picsum.photos/id/" . $randomize_number . "/";
    $url = "{$width}/{$height}.jpg";
    return $baseUrl . $url;
}

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $name1 = 'admin';
        $user1 = factory(User::class)->create([
            'email' => 'admin@admin.com',
            'username' => $name1,
            "url" => $name1,
            'screen_name' => $name1,
        ]);
        $name2 = 'test';
        $user2 = factory(User::class)->create([
            'email' => 'test@test.com',
            'username' => $name2,
            "url" => $name2,
            'screen_name' => $name2,
        ]);
        factory(Profile::class)->create([
            'user_id' => $user1->id,
        ]);

        factory(Profile::class)->create([
            'user_id' => $user2->id,
        ]);

        // attach images to custom users
        $imageUrl = imageUrl(300, 300);

        $media = $user2->addMediaFromUrl($imageUrl)->toMediaCollection('avatars');
        unlink($media->getPath()); // delete original after conversions

        // create custom subscriptions
        $user1->subscribed()->sync($user2->id);

        $user2->subscribed()->sync($user1->id);

        // create random profiles and subscriptions

        factory(Profile::class, 10)->create()->each(function ($i) use ($user1) {
            $i->user->subscribed()->attach($user1->id);
        });

        factory(Profile::class, 10)->create()->each(function ($i) use ($user2) {
            $i->user->subscribed()->attach($user2->id);
        });

        // create tracks for first user
        factory(Tag::class, 20)->create();
        factory(Genre::class, 20)->create();

        // tracks
        factory(Track::class, 20)->create();

        // comments
        factory(Comment::class, 100)->create();

    }
}
