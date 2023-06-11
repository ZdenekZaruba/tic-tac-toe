<?php

// Načtení herního pole
$board = json_decode(file_get_contents('board.json'), true);

// Získání volných pozic
$availableMoves = getAvailableMoves($board);

// Výpočet nejlepšího tahu
$bestMove = minimax($board, $availableMoves, true);

// Provedení tahu
makeMove($board, $bestMove, 'O');

// Uložení herního pole
file_put_contents('board.json', json_encode($board));

// Odpověď AI s vybraným tahem
$response = [
    'row' => $bestMove['row'],
    'col' => $bestMove['col'],
];
echo json_encode($response);

/**
 * Funkce pro výpočet nejlepšího tahu pomocí algoritmu Minimax s alpha-beta prořezáváním.
 * @param array $board Herní pole
 * @param array $moves Dostupné tahy
 * @param bool $maximizingPlayer Určuje, zda je aktuální hráč maximalizující nebo minimalizující
 * @param int $depth Hloubka rekurze
 * @param int $alpha Alpha hodnota pro alpha-beta prořezávání
 * @param int $beta Beta hodnota pro alpha-beta prořezávání
 * @return array Nejlepší tah
 */
function minimax($board, $moves, $maximizingPlayer, $depth = 0, $alpha = -INF, $beta = INF) {
    // Omezení maximální hloubky rekurze
    if ($depth >= 3) {
        return ['score' => evaluate($board)];
    }

    // Výchozí hodnota nejlepšího skóre
    $bestScore = $maximizingPlayer ? -INF : INF;

    // Nejlepší tah
    $bestMove = null;

    // Procházení dostupných tahů
    foreach ($moves as $move) {
        // Provedení tahu
        makeMove($board, $move, $maximizingPlayer ? 'O' : 'X');

        // Získání dostupných tahů po provedení tahu
        $nextMoves = getAvailableMoves($board);

        // Rekurzivní volání Minimax
        $result = minimax($board, $nextMoves, !$maximizingPlayer, $depth + 1, $alpha, $beta);

        // Vrácení herního pole do původního stavu
        undoMove($board, $move);

        // Aktualizace nejlepšího skóre a tahu
        if ($maximizingPlayer) {
            if ($result['score'] > $bestScore) {
                $bestScore = $result['score'];
                $bestMove = $move;
            }
            $alpha = max($alpha, $bestScore);
        } else {
            if ($result['score'] < $bestScore) {
                $bestScore = $result['score'];
                $bestMove = $move;
            }
            $beta = min($beta, $bestScore);
        }

        // Alpha-beta prořezávání
        if ($alpha >= $beta) {
            break;
        }
    }

    // Vrácení nejlepšího tahu
    return ['move' => $bestMove, 'score' => $bestScore];
}

/**
 * Funkce pro vyhodnocení skóre herního pole.
 * @param array $board Herní pole
 * @return int Skóre
 */
function evaluate($board) {
    // Implementace vyhodnocení skóre herního pole
    // ...

    return 0; // Návratová hodnota skóre
}

/**
 * Funkce pro provedení tahu na herním poli.
 * @param array $board Herní pole
 * @param array $move Tah
 * @param string $player Symbol hráče
 */
function makeMove(&$board, $move, $player) {
    $row = $move['row'];
    $col = $move['col'];
    $board[$row][$col] = $player;
}

/**
 * Funkce pro vrácení tahu na herním poli.
 * @param array $board Herní pole
 * @param array $move Tah
 */
function undoMove(&$board, $move) {
    $row = $move['row'];
    $col = $move['col'];
    $board[$row][$col] = '';
}

/**
 * Funkce pro získání dostupných tahů na herním poli.
 * @param array $board Herní pole
 * @return array Dostupné tahy
 */
function getAvailableMoves($board) {
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
