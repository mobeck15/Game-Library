<?php
// @codeCoverageIgnoreStart
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? "..";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
require_once $GLOBALS['rootpath']."/ajax/AutocompleteHandler.php";

//TODO: header invalidates test scripts
//header('Content-Type: application/json');

try {
    $conn = get_db_connection();
    $handler = new AutocompleteHandler($conn);

    $queryType = $_GET['querytype'] ?? 'Game';
    $term = $_GET['term'] ?? '';

    if ($term === '') {
        echo json_encode([]);
        exit;
    }

    $results = $handler->search($queryType, $term);
    echo json_encode($results);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
// @codeCoverageIgnoreEnd