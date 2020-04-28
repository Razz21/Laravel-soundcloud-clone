<?php

namespace App\Http\Controllers;

use App\Events\FileProcessingEvent;
use App\Http\Requests\Track\StoreTracksRequest;
use App\Jobs\Files\ConvertForStreaming;
use App\Track;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use \Imtigger\LaravelJobStatus\JobStatus;

class TrackController extends Controller
{
    use DispatchesJobs;

    public function __construct()
    {
        $this->middleware(['auth'])->only(['create', 'store']);
    }

    public function create()
    {
        $profile = auth()->user()->profile;
        return view('files.create', compact('profile'));
    }

    public function show(Track $track)
    {
        if (request()->wantsJson()) {
            return $track;
        }

        return view('files.show', compact('track'));
    }

    public function store(StoreTracksRequest $request)
    {

        $fullName = $request->file->getClientOriginalName();
        $extension = $request->file->getClientOriginalExtension();
        // $onlyName = explode('.' . $extension, $fullName)[0]; // without extension

        $filename = md5($fullName . now()) . '.' . $extension; // save as temp file with original extension

        $track = auth()->user()->tracks()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            "description" => $request->description,
        ]);
        $userId = auth()->user()->id;
        $tempFilePath = $request->file('file')->storeAs("temp/{$userId}", $filename);

        $job = (new ConvertForStreaming($track, $tempFilePath))->onConnection('database');
        $this->dispatch($job);
        $jobStatusId = $job->getJobStatusId();
        $jobStatus = JobStatus::find($jobStatusId);
        // queued
        event(new FileProcessingEvent($jobStatus, $track));

        return $jobStatus;
    }

}
