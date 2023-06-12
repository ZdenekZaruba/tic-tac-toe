<?php

// Načtení historických dat
$history = json_decode(file_get_contents('history.json'), true);

// Načtení momentálního stavu pole
$currentBoard = json_decode(file_get_contents('currentBoard.json'), true);

// Trénování AI na historických datech
trainAI($history);

// Aktualizace AI na základě aktuálního stavu pole
updateAI($currentBoard);

// Odpověď AI s vybraným tahem
$response = [
    'row' => $bestMove['row'],
    'col' => $bestMove['col'],
];
echo json_encode($response);

/**
 * Funkce pro trénování AI na historických datech.
 * @param array $history Historická data
 */
function trainAI($history) {
    // Načtení vah AI
    $weights = json_decode(file_get_contents('weights.json'), true);

    foreach ($history as $game) {
        // Extrahování informací z historie hry
        $board = extractBoard($game['board']);
        $winner = $game['result'];

        // Vyhodnocení skóre herního stavu
        $score = evaluate($board);

        // Aktualizace váh na základě skóre a vítěze hry
        if ($winner === 'AI') {
            $weights = updateWeights($weights, $board, $score, 1.0);
        } elseif ($winner === 'Opponent') {
            $weights = updateWeights($weights, $board, $score, -1.0);
        }
    }

    // Uložení aktualizovaných vah AI
    file_put_contents('weights.json', json_encode($weights));
}

/**
 * Funkce pro extrahování herního pole z dat.
 * @param array $data Herní data
 * @return array Herní pole
 */
function extractBoard($data) {
    $board = [];

    foreach ($data as $row) {
        $boardRow = [];

        foreach ($row as $cell) {
            $boardRow[] = $cell['player'];
        }

        $board[] = $boardRow;
    }

    return $board;
}

/**
 * Funkce pro aktualizaci vah na základě herního stavu, skóre a multiplikátoru váhy.
 * @param array $weights Váhy
 * @param array $board Herní pole
 * @param int $score Skóre
 * @param float $weightMultiplier Multiplikátor váhy
 * @return array Aktualizované váhy
 */
function updateWeights($weights, $board, $score, $weightMultiplier)
{
    // Procházení herního pole a aktualizace váh na základě symbolů
    foreach ($board as $row => $boardRow) {
        foreach ($boardRow as $col => $symbol) {
            if ($symbol === 'AI') {
                // Zvýšení váhy pro symbol AI
                $weights[$row][$col] += $score * $weightMultiplier;
            } elseif ($symbol === 'Opponent') {
                // Snížení váhy pro symbol hráče
                $weights[$row][$col] -= $score * $weightMultiplier;
            }
        }
    }

    return $weights; // Návrat aktualizovaných vah
}

/**
 * Funkce pro vyhodnocení herního stavu.
 * @param array $board Herní pole
 * @return float Skóre herního stavu
 */
function evaluate($board) {
    $score = 0;

    // Vyhodnocení herního stavu na základě vlastní logiky
    // Například implementace blokování tahů hráče a snahy o výhru

    // Procházení řádků
    for ($row = 0; $row < 3; $row++) {
        $playerCount = 0; // Počet symbolů hráče v řádku
        $aiCount = 0; // Počet symbolů AI v řádku

        for ($col = 0; $col < 3; $col++) {
            if ($board[$row][$col] === 'AI') {
                $aiCount++;
            } elseif ($board[$row][$col] === 'Opponent') {
                $playerCount++;
            }
        }

        // Logika pro blokování a snahu o výhru
        if ($aiCount === 2 && $playerCount === 0) {
            $score += 10; // AI má možnost vyhrát
        } elseif ($playerCount === 2 && $aiCount === 0) {
            $score += 5; // Hráč má možnost vyhrát, blokujeme
        }
    }

    // Procházení sloupců
    for ($col = 0; $col < 3; $col++) {
        $playerCount = 0; // Počet symbolů hráče ve sloupci
        $aiCount = 0; // Počet symbolů AI ve sloupci

        for ($row = 0; $row < 3; $row++) {
            if ($board[$row][$col] === 'AI') {
                $aiCount++;
            } elseif ($board[$row][$col] === 'Opponent') {
                $playerCount++;
            }
        }

        // Logika pro blokování a snahu o výhru
        if ($aiCount === 2 && $playerCount === 0) {
            $score += 10; // AI má možnost vyhrát
        } elseif ($playerCount === 2 && $aiCount === 0) {
            $score += 5; // Hráč má možnost vyhrát, blokujeme
        }
    }

    // Procházení diagonál
    $playerCount = 0; // Počet symbolů hráče v diagonále
    $aiCount = 0; // Počet symbolů AI v diagonále

    for ($i = 0; $i < 3; $i++) {
        if ($board[$i][$i] === 'AI') {
            $aiCount++;
        } elseif ($board[$i][$i] === 'Opponent') {
            $playerCount++;
        }
    }

    // Logika pro blokování a snahu o výhru
    if ($aiCount === 2 && $playerCount === 0) {
        $score += 10; // AI má možnost vyhrát
    } elseif ($playerCount === 2 && $aiCount === 0) {
        $score += 5; // Hráč má možnost vyhrát, blokujeme
    }

    $playerCount = 0; // Počet symbolů hráče v druhé diagonále
    $aiCount = 0; // Počet symbolů AI v druhé diagonále

    for ($i = 0; $i < 3; $i++) {
        if ($board[$i][2 - $i] === 'AI') {
            $aiCount++;
        } elseif ($board[$i][2 - $i] === 'Opponent') {
            $playerCount++;
        }
    }

    // Logika pro blokování a snahu o výhru
    if ($aiCount === 2 && $playerCount === 0) {
        $score += 10; // AI má možnost vyhrát
    } elseif ($playerCount === 2 && $aiCount === 0) {
        $score += 5; // Hráč má možnost vyhrát, blokujeme
    }

    return $score;
}

/**
 * Funkce pro výpočet nejlepšího tahu na základě váh a herního stavu.
 * @param array $board Herní pole
 * @param array $weights Váhy
 * @return array Nejlepší tah
 */
function getBestMove($board, $weights) {
    $availableMoves = getAvailableMoves($board);
    $bestMove = $availableMoves[0]; // Přednastavení prvního dostupného tahu jako nejlepšího
    $bestScore = -INF;

    foreach ($availableMoves as $move) {
        $newBoard = $board;
        makeMove($newBoard, $move, 'AI');
        $score = evaluate($newBoard);

        if ($score > $bestScore) {
            $bestScore = $score;
            $bestMove = $move;
        }
    }

    return $bestMove;
}

/**
 * Funkce pro aktualizaci AI na základě aktuálního stavu pole.
 * @param array $currentBoard Aktuální stav pole
 */
function updateAI($currentBoard) {
    // Načtení vah AI
    $weights = json_decode(file_get_contents('weights.json'), true);

    // Vyhodnocení skóre aktuálního stavu pole
    $score = evaluate($currentBoard);

    // Výpočet nejlepšího tahu na základě váh a skóre
    $bestMove = getBestMove($currentBoard, $weights);

    // Provádění tahu a aktualizace herního pole
    makeMove($currentBoard, $bestMove, 'AI');

    // Uložení aktualizovaného stavu pole
    file_put_contents('currentBoard.json', json_encode($currentBoard));
    // Uložení aktualizovaných váh AI
    file_put_contents('weights.json', json_encode($weights));
}

// Zbylé funkce z původního skriptu...

/**
 * Funkce pro provedení tahu na herním poli.
 * @param array $board Herní pole
 * @param array $move Tah
 * @param string $player Symbol hráče
 */
function makeMove(&$board, $move, $player)
{
    $row = $move['row'];
    $col = $move['col'];
    $board[$row][$col] = $player;
}

/**
 * Funkce pro získání dostupných tahů na herním poli.
 * @param array $board Herní pole
 * @return array Dostupné tahy
 */
function getAvailableMoves($board)
{
    $moves = [];
    $rows = count($board);
    $cols = count($board[0]);

    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            if ($board[$row][$col] === '') {
                $moves[] = ['row' => $row, 'col' => $col];
            }
        }
    }

    return $moves;
}
?>
