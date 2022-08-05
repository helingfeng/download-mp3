<?php

use DownloadMp3\Downloader;

include __DIR__ . "/vendor/autoload.php";

$name = $_GET['name'];

if (empty($name)) {
    header('Content-Type:application/json; charset=utf-8');
    $response = array('message' => '歌曲名称不能为空');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

$downloader = new Downloader();
$music = $downloader->downloadMusic($name, 0);

header("Content-Type: audio/mpeg");
header('Content-Disposition: attachment; filename="' . $music['name'] . '.mp3"');
echo file_get_contents($music['mp3']);
exit();