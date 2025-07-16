<?php
declare(strict_types=1);
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? "..";
include_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";

header('Content-Type: text/plain');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");


$steamAPI = new SteamAPI();

if(isset($_GET['game']))
{   
    $steamAPI = new SteamAPI($_GET['game']);
}
 
$output="";
if(isset($_GET['api']))
{
    $output = $steamAPI->GetSteamAPI($_GET['api']);
}

$json = json_encode($output);

echo $json;