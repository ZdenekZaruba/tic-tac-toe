<?php
// Přijetí herních dat z POST požadavku
$data = file_get_contents('php://input');
$gameData = json_decode($data, true);

// Získání aktuálního obsahu souboru history.json
$historyData = file_get_contents('history.json');
$history = [];

if (!empty($historyData)) {
    // Pokud soubor history.json již obsahuje data, dekódujeme je
    $history = json_decode($historyData, true);
}

// Generování klíče pro nový záznam
$newKey = 'match' . (count($history) + 1);

// Upravení herního pole na velikost 10x10
$board = [];
for ($i = 0; $i < 10; $i++) {
    $board[] = array_fill(0, 10, "");
}
$gameData['board'] = $board;

// Odstranění klíče "playerMoves" z herních dat
unset($gameData['playerMoves']);

// Přidání nového záznamu do pole historie
$history[$newKey] = [
    'board' => $gameData['board'],
    'result' => $gameData['result']
];

// Uložení aktualizovaného pole historie do souboru history.json
$historyString = json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$historyStringFormatted = str_replace('},{', "},\n{", $historyString);
file_put_contents('history.json', $historyStringFormatted);

// Odeslání odpovědi zpět
http_response_code(200);
?>
