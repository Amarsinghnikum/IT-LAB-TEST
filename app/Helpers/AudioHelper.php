<?php

namespace App\Helpers;

use getID3;

class AudioHelper
{
    public static function getAudioLength($filePath)
{
    require_once base_path('vendor/james-heinrich/getid3/getid3/getid3.php');

    $getID3 = new getID3();

    $fileInfo = $getID3->analyze($filePath);

    if (isset($fileInfo['playtime_seconds'])) {
        $playtimeSeconds = $fileInfo['playtime_seconds'];
        return $playtimeSeconds;
    } else {
        return null;
    }
}
}
