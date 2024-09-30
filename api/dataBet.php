<?php

require_once '../vendor/autoload.php';

use App\Controllers\ApiController;
use App\Repositories\RelatedDataRepository;
use App\Services\DBService;

$host = $_ENV['MYSQL_HOST'];
$user = $_ENV['MYSQL_USER'];
$password = $_ENV['MYSQL_PASSWORD'];
$db = $_ENV['MYSQL_DATABASE'];
$port = $_ENV['MYSQL_PORT'];

$db = new DBService($host, $user, $password, $db, $port);
$relatedDataRepository = new RelatedDataRepository($db);
$apiController = new ApiController($relatedDataRepository);

$apiController->sendDataBet();