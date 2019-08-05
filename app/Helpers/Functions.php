<?php

/**
 * General helper functions that don't have a batter place
 */


/**
* returns the value of $name setting as stored in DB.
*/
function setting($name, $default = false)
{
    return \App\Setting::get($name, $default);
}

function sizeForHumans($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2).'GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2).'MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2).'KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function intervalToMinutes($interval)
{
    $minutes = 60 * 24;

    switch ($interval) {
        case 'hourly':
        $minutes = 60;
        break;
        case 'daily':
        $minutes = 60 * 24;
        break;
        case 'weekly':
        $minutes = 60 * 24 * 7;
        break;
        case 'biweekly':
        $minutes = 60 * 24 * 14;
        break;
        case 'monthly':
        $minutes = 60 * 24 * 30;
        break;
        case 'never':
        $minutes = -1;
        break;
    }

    return $minutes;
}

function minutesToInterval($minutes)
{
    $interval = 'daily';

    switch ($minutes) {
        case 60:
        $interval = 'hourly';
        break;
        case 60 * 24:
        $interval = 'daily';
        break;
        case 60 * 24 * 7:
        $interval = 'weekly';
        break;
        case 60 * 24 * 14:
        $interval = 'biweekly';
        break;
        case 60 * 24 * 30:
        $interval = 'monthly';
        break;
        case -1:
        $interval = 'never';
        break;
    }

    return $interval;
}

// this one line replace almost all laracast flash tutorial that became bloated for our use case
function flash($message)
{
    session()->push('messages', $message);
}
