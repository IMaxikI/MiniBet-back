<?php

require_once '../../vendor/autoload.php';

use App\Repositories\CountryRepository;
use App\Repositories\EventRepository;
use App\Repositories\MarketRepository;
use App\Repositories\OutcomeRepository;
use App\Repositories\SportRepository;
use App\Repositories\TournamentRepository;
use App\Services\DBService;
use App\Services\KralbetImport;
use App\Services\KralbetParser;
use WebSocket\Client;

const URI = 'wss://srv.kralbet915.com/sport/?EIO=3&transport=websocket';
const SUBSCRIBE_MESS = '42["subscribe-PreliveEvents",{"market_group":"prelive","locale":"en_US"}]';

$socket = new Client(URI);

$kralbetParser = new KralbetParser($socket, SUBSCRIBE_MESS);
$structure = $kralbetParser->parse();

$host = $_ENV['MYSQL_HOST'];
$user = $_ENV['MYSQL_USER'];
$password = $_ENV['MYSQL_PASSWORD'];
$db = $_ENV['MYSQL_DATABASE'];
$port = $_ENV['MYSQL_PORT'];

$db = new DBService($host, $user, $password, $db, $port);

$sportRepository = new SportRepository($db);
$countryRepository = new CountryRepository($db);
$tournamentRepository = new TournamentRepository($db);
$eventRepository = new EventRepository($db);
$marketRepository = new MarketRepository($db);
$outcomeRepository = new OutcomeRepository($db);

$kralbetImport = new KralbetImport(
    $countryRepository, $sportRepository,
    $tournamentRepository, $eventRepository,
    $marketRepository, $outcomeRepository
);

$kralbetImport->import($structure);