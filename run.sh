#!/usr/bin/env php
<?php

include __DIR__ . "/vendor/autoload.php";

use Symfony\Component\Console\Application;
use \DownloadMp3\Command;

$application = new Application();

// ... register commands / 注册命令
$application->add(new Command());
$application->run();
