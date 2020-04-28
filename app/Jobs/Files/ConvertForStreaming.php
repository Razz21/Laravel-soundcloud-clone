<?php

namespace App\Jobs\Files;

use App\Events\FileProcessingEvent;
use App\Helpers\WavToJson;
use App\Track;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;
use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;
use \Imtigger\LaravelJobStatus\JobStatus;

// use Pbmedia\LaravelFFMpeg\FFMpeg;

class ConvertForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    public $track;
    public $jobStatus;
    public $tempFile;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Track $track, string $tempFile)
    {
        $this->prepareStatus();
        $this->track = $track;
        $this->tempFile = $tempFile;
        $this->jobStatus = JobStatus::findOrFail($this->getJobStatusId());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::debug('ConverForStreaming START');
        $this->setProgressMax(100);

        event(new FileProcessingEvent($this->jobStatus, $this->track));
        try {

            $filepath = 'public/' . $this->tempFile;
            $indexFile = 'index.m3u8';

            $format = (new \FFMpeg\Format\Audio\Mp3)->setAudioKiloBitrate(128)
                ->on('progress', function ($audio, $format, $percentage) {
                    $this->setProgressNow($percentage);

                    event(new FileProcessingEvent($this->jobStatus, $this->track));
                });

            $media = FFMpeg::fromDisk('local')->open($filepath);
            $audio_length = $media->getDurationInSeconds();

            $media->addFilter(new \FFMpeg\Filters\Audio\SimpleFilter([
                "-vn",
                "-hls_list_size", "0", "-hls_time", "10",
                // "-f", "segment", "-segment_time", "60", "-segment_list_size", "0", "output%03d.ts", //"-segment_format", "mpegts",
            ]))

                ->export()
                ->toDisk('tracks')
                ->inFormat($format)
                ->save($this->track->id . "/" . $indexFile);

            $waveformData = WavToJson::convert($filepath, $this->track->id);

            // \Log::info('wavetojson', compact('waveformData'));

            $this->track->update([
                'audio_length' => $audio_length,
                'wave' => $waveformData,
                "url" => "tracks/{$this->track->id}/" . $indexFile,
            ]);

            /*
            Note: 'onprogress' event sometimes does not send 100% progress on finish,
            'finished' status need to be send explictly to trigger websockets
             */
            $this->setProgressNow(100);
            $this->update(['status' => 'finished']);

            // $status = $this->jobStatus;
            // \Log::info('jobStatus', compact('status'));

            event(new FileProcessingEvent($this->jobStatus, $this->track));
        } catch (\Exception $e) {
            event(new FileProcessingEvent($this->jobStatus, $this->track));
            \Log::error('ConverForStreaming', compact('e'));
        } finally {
            \Storage::delete($this->tempFile);
        }
    }

    public function failed()
    {
        $this->track->delete();
    }
}
