<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\Console\Application;

$application = new \Symfony\Component\Console\Application();
$application->add(new \Devian2011\Seeder\Commands\FillDataCommand());
$application->run();

