<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TimeFormatProvider extends ServiceProvider
{
    public static function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return $hours > 0 ? "{$hours} óra és {$remainingMinutes} perc" : "{$remainingMinutes} perc";
    }
    public static function calculateTimes($start, $end): array
    {
        $totalSeconds = $end->getTimestamp() - $start->getTimestamp();
        return [
            'seconds' => $totalSeconds,
            'minutes' => floor($totalSeconds / 60),
            'hours' => floor($totalSeconds / 3600),
            'days' => floor($totalSeconds / (24 * 3600)),
            'remainingHours' => floor(($totalSeconds % (24 * 3600)) / 3600),
            'remainingMinutes' => floor(($totalSeconds % 3600) / 60)
        ];
    }
}
