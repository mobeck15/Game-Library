<?php
declare(strict_types=1);

// @codeCoverageIgnoreStart
require_once dirname(__DIR__) . "/inc/SteamAPI.class.php";

$game = $_GET['game'] ?? null;
$steamAPI = $game ? new SteamAPI($game) : new SteamAPI();
$handler = new SteamAPIHandler($steamAPI);
$handler->sendHeaders();
echo $handler->handleRequest($_GET);
// @codeCoverageIgnoreEnd
