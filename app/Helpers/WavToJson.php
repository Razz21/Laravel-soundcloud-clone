<?php

namespace App\Helpers;

use Exception;
use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFmpeg;

class WavToJson
{
    private static $tempDirectory = 'temp/';
    private static $DETAIL = 5;

    private static function filterData($audioBuffer, $samples = 170)
    {
        $rawData = array_values($audioBuffer);

        $blockSize = floor(count($rawData) / $samples); // the number of samples in each subdivision
        $filteredData = array();

        for ($i = 0; $i < $samples; $i++) {
            $blockStart = $blockSize * $i; // the location of the first sample in the block
            $sum = 0;
            for ($j = 0; $j < $blockSize; $j++) {
                $sum += abs($rawData[$blockStart + $j]); // find the sum of all the samples in the block
            }
            array_push($filteredData, $sum / $blockSize); // divide the sum by the block size to get the average
        }
        return $filteredData;
    }

    private static function normalizeData($filteredData, $precision = 16)
    {
        $max = max($filteredData);
        $multiplier = pow($max, -1);

        return array_map(function ($val) use ($multiplier, $precision) {
            $result = $val * $multiplier;
            return (float) number_format($result, $precision);

        }, $filteredData);
    }

    private static function findValues($byte1, $byte2)
    {
        $byte1 = hexdec(bin2hex($byte1));
        $byte2 = hexdec(bin2hex($byte2));
        return ($byte1 + ($byte2 * 256));
    }

    public static function convert($filepath, $id)
    {
        // $tempname = tempnam(self::$tempDirectory, "wav2json_");
        $tempFile = self::$tempDirectory . "wav2json_{$id}.wav";

        $format = (new \FFMpeg\Format\Audio\Wav)
            ->setAudioCodec('pcm_s16le')
            ->setAudioChannels(1);

        FFmpeg::fromDisk('local')
            ->open($filepath)
            ->addFilter(new \FFMpeg\Filters\Audio\SimpleFilter([
                "-ar", "8000", "-af", "volume=0.1",
            ]))
            ->export()
            ->inFormat($format)
            ->save($tempFile);

        try {
            $json = new \stdClass();
            $json->data = array();
            $array = &$json->data;
            $path = storage_path($tempFile);

            // \Log::info('filename', compact('path'));

            $filename = storage_path('app/') . $tempFile;
            // $filename = str_replace('\\', '/', $filename);
            /**
             * Below as posted by "zvoneM" on
             * http://forums.devshed.com/php-development-5/reading-16-bit-wav-file-318740.html
             * as findValues() defined above
             * Translated from Croation to English - July 11, 2011
             */
            $handle = fopen($filename, "r");
            // wav file header retrieval
            $heading[] = fread($handle, 4);
            $heading[] = bin2hex(fread($handle, 4));
            $heading[] = fread($handle, 4);
            $heading[] = fread($handle, 4);
            $heading[] = bin2hex(fread($handle, 4));
            $heading[] = bin2hex(fread($handle, 2));
            $heading[] = bin2hex(fread($handle, 2));
            $heading[] = bin2hex(fread($handle, 4));
            $heading[] = bin2hex(fread($handle, 4));
            $heading[] = bin2hex(fread($handle, 2));
            $heading[] = bin2hex(fread($handle, 2));
            $heading[] = fread($handle, 4);
            $heading[] = bin2hex(fread($handle, 4));

            // wav bitrate
            $peek = hexdec(substr($heading[10], 0, 2));
            $byte = $peek / 8;

            // checking whether a mono or stereo wav
            $channel = hexdec(substr($heading[6], 0, 2));

            $ratio = ($channel == 2 ? 40 : 80);

            // start putting together the initial canvas
            // $data_size = (size_of_file - header_bytes_read) / skipped_bytes + 1
            $data_size = floor((filesize($filename) - 44) / ($ratio + $byte) + 1);
            $data_point = 0;

            while (!feof($handle) && $data_point < $data_size) {
                if ($data_point++ % self::$DETAIL == 0) {
                    $bytes = array();

                    // get number of bytes depending on bitrate
                    for ($i = 0; $i < $byte; $i++) {
                        $bytes[$i] = fgetc($handle);
                    }

                    switch ($byte) {
                        // get value for 8-bit wav
                        case 1:
                            $data = self::findValues($bytes[0], $bytes[1]);
                            break;
                        // get value for 16-bit wav
                        case 2:
                            if (ord($bytes[1]) & 128) {
                                $temp = 0;
                            } else {
                                $temp = 128;
                            }

                            $temp = chr((ord($bytes[1]) & 127) + $temp);
                            $data = floor(self::findValues($bytes[0], $temp) / 256);
                            break;
                    }

                    // skip bytes for memory optimization
                    fseek($handle, $ratio, SEEK_CUR);

                    // draw this data point
                    // relative value based on height of image being generated
                    // data values can range between 0 and 255
                    $v = ($data / 255);

                    $array[] = (float) number_format(abs(0.5 - $v), 4);

                } else {
                    // skip this one due to lack of detail
                    fseek($handle, $ratio + $byte, SEEK_CUR);
                }
            }

            // close and cleanup
            fclose($handle);

        } catch (Exception $error) {
            \Log::error('error', compact('error'));
            return false;
        } finally {
            // delete processed wav file
            unlink($filename);
        }
        $normalized = self::normalizeData(self::filterData($json->data));

        return array_values($normalized);
    }
}
