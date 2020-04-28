<?php

namespace App\Events;

use App\Track;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use \Imtigger\LaravelJobStatus\JobStatus;

class FileProcessingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $job;
    public $track_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(JobStatus $job, Track $track)
    {
        $this->job = $job;
        $this->track_id = $track->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('upload');
    }
    public function broadcastAs()
    {
        return 'file.progress';
    }
    public function broadcastWith()
    {
        return [
            'id' => $this->job->id,
            'status' => $this->job->status,
            'progress_now' => $this->job->progress_now,
            'description' => $this->job->description,
            'created_at' => $this->job->created_at,
            'updated_at' => $this->job->updated_at,
            'finished_at' => $this->job->finished_at,
            'started_at' => $this->job->started_at,
        ];
    }
}
