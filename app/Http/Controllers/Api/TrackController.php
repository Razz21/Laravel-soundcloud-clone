<?php

namespace App\Http\Controllers\Api;

use App\Events\FileProcessingEvent;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Requests\Track\StoreTracksRequest;
use App\Http\Resources\TrackResource;
use App\Jobs\Files\ConvertForStreaming;
use App\Tag;
use App\Track;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Imtigger\LaravelJobStatus\JobStatus;

class TrackController extends Controller
{
    use DispatchesJobs;

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function show(Track $track)
    {
        return TrackResource::make($track);
    }

    public function store(StoreTracksRequest $request)
    {
        $file = null;
        try {
            $fullName = $request->file->getClientOriginalName();
            $extension = $request->file->getClientOriginalExtension();
            // $onlyName = explode('.' . $extension, $fullName)[0]; // without extension

            $filename = md5($fullName . now()) . '.' . $extension; // save as temp file with original extension

            $file = auth()->user()->tracks()->create([
                'title' => $request->title,
                'slug' => $request->slug, // TODO unique slug for user tracks
                "description" => $request->description,
                "genre_id" => $request->genre,
            ]);

            if ($request->tags) {
                $tagsArr = $this->handleTags($request->tags);
                $file->tags()->sync($tagsArr, true);
            }
            if ($request->hasFile('cover')) {
                // allow only one image
                $file->clearMediaCollection('covers');
                $media = $file->addMediaFromRequest('cover')->toMediaCollection('covers');
                unlink($media->getPath()); // delete original after conversions
            }
            $userId = auth()->user()->id;
            $tempFilePath = $request->file('file')->storeAs("temp/{$userId}", $filename);

            $job = (new ConvertForStreaming($file, $tempFilePath))->onConnection('database');
            $this->dispatch($job);
            $jobStatusId = $job->getJobStatusId();
            $jobStatus = JobStatus::find($jobStatusId);
            // queued
            event(new FileProcessingEvent($jobStatus, $file));
            return $jobStatus;
        } catch (\Exception $e) {
            if ($file) {
                $file->delete();
            }
            return response()->json($e, 500);
        }
    }
    /**
     * Process tags array.
     *
     * @param  array $tags
     * @return array
     */
    public function handleTags(array $tags)
    {
        $tagsArr = array();
        $existing = array();
        foreach ($tags as $tag) {
            if ($json_tag = Utils::is_json($tag)) {
                // potentially existing tag object/array selected by user,
                // stored in array to not fire eloquent check every iteration, null if fail
                $existing[] = (int) $json_tag->id ?? null;
            } else {
                // new tag, but first check, if not exist already
                $t = Tag::firstOrCreate(['name' => $tag]);
                $tagsArr[] = $t->id;
            }
        }
        $checkExisting = Tag::find($existing)->pluck('id')->toArray(); // find and return existing tags ids
        return array_unique(array_merge($tagsArr, $checkExisting)); // return unique ids array
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
