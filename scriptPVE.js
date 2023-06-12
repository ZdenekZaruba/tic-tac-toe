// Globální proměnné
var currentPlayer = 'X';
var gameOver = false;

// Získání herního pole
var board = document.querySelector('.board');

// Přidání event listeneru na kliknutí na herní pole
board.addEventListener('click', function (event) {
    var cell = event.target;

    // Kontrola zda je políčko prázdné
    if (cell.innerHTML === '') {
        // Zápis hráčova tahu do políčka
        cell.innerHTML = currentPlayer;

        // Kontrola výhry
        if (checkWin()) {
            alert('Hráč ' + currentPlayer + ' vyhrál!');
            endGame(currentPlayer);
            return;
        }

        // Kontrola remízy
        if (checkDraw()) {
            alert('Remíza!');
            endGame('draw');
            return;
        }

        // Přepnutí hráče
        switchPlayer();

        // Tah AI
        makeAITurn();
    }
});

// Funkce pro přepnutí hráče
function switchPlayer() {
    currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
}

// Funkce pro kontrolu výhry
function checkWin() {
    var cells = document.querySelectorAll('.board td');

    // Kontrola výhry - horizontální řádky
    for (var i = 0; i < 10; i++) {
        for (var j = 0; j < 8; j++) {
            var currentCell = cells[i * 10 + j];
            var next1 = cells[i * 10 + j + 1];
            var next2 = cells[i * 10 + j + 2];

            if (
                currentCell.innerHTML === currentPlayer &&
                next1.innerHTML === currentPlayer &&
                next2.innerHTML === currentPlayer
            ) {
                return true;
            }
        }
    }

    // Kontrola výhry - vertikální sloupce
    for (var i = 0; i < 8; i++) {
        for (var j = 0; j < 10; j++) {
            var currentCell = cells[i * 10 + j];
            var next1 = cells[(i + 1) * 10 + j];
            var next2 = cells[(i + 2) * 10 + j];

            if (
                currentCell.innerHTML === currentPlayer &&
                next1.innerHTML === currentPlayer &&
                next2.innerHTML === currentPlayer
            ) {
                return true;
            }
        }
    }

    // Kontrola výhry - diagonály zleva doprava
    for (var i = 0; i < 8; i++) {
        for (var j = 0; j < 8; j++) {
            var currentCell = cells[i * 10 + j];
            var next1 = cells[(i + 1) * 10 + j + 1];
            var next2 = cells[(i + 2) * 10 + j + 2];

            if (
                currentCell.innerHTML === currentPlayer &&
                next1.innerHTML === currentPlayer &&
                next2.innerHTML === currentPlayer
            ) {
                return true;
            }
        }
    }

    // Kontrola výhry - diagonály zprava doleva
    for (var i = 0; i < 8; i++) {
        for (var j = 2; j < 10; j++) {
            var currentCell = cells[i * 10 + j];
            var next1 = cells[(i + 1) * 10 + j - 1];
            var next2 = cells[(i + 2) * 10 + j - 2];

            if (
                currentCell.innerHTML === currentPlayer &&
                next1.innerHTML === currentPlayer &&
                next2.innerHTML === currentPlayer
            ) {
                return true;
            }
        }
    }

    return false;
}

// Funkce pro kontrolu remízy
function checkDraw() {
    var cells = document.querySelectorAll('.board td');

    for (var i = 0; i < cells.length; i++) {
        if (cells[i].innerHTML === '') {
            return false;
        }
    }

    return true;
}

function endGame(result) {
    // Získání herního pole
    var cells = document.querySelectorAll('.board td');

    currentPlayer = 'X';

    // Vytvoření objektu s daty konce hry
    var gameData = {
        board: [],
        playerMoves: [],
        result: result,
        playerWon: result === 'draw' ? ['draw'] : [currentPlayer] // Upravený zápis
    };

    // Uložení herního pole do objektu
    for (var i = 0; i < cells.length; i++) {
        gameData.board.push(cells[i].innerHTML);
    }

    // Odeslání dat do history.php pomocí AJAX nebo Fetch API
    fetch('history.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(gameData)
    })
        .then(function (response) {
            if (response.ok) {
                alert('Hra byla uložena do historie.');
                restartGame();
            } else {
                throw new Error('Chyba při ukládání hry do historie.');
            }
        })
        .catch(function (error) {
            console.log('Chyba:', error);
            // Zde můžete zpracovat chybu, například zobrazit uživateli upozornění
        });
}


function restartGame() {
    // Získání herního pole
    var cells = document.querySelectorAll('.board td');

    // Vyprázdnění herního pole
    for (var i = 0; i < cells.length; i++) {
        cells[i].innerHTML = '';
    }

    // Resetování globálních proměnných
    currentPlayer = 'X';
    gameOver = false;

    // Zde můžete provést další akce, které jsou součástí restartu hry

    // Například: Zobrazení upozornění nebo jiné akce pro uživatele

    // Funkce pro tah umělé inteligence (pokud je součástí hry) můžete volat zde

    // Žádné další zápisy do historie nejsou potřeba

    console.log('Hra byla restartována.');
}

// Funkce pro tah AI
function makeAITurn() {
    if (currentPlayer === 'O') {
        var cells = document.querySelectorAll('.board td');
        var emptyCells = [];

        // Najdi volná políčka
        for (var i = 0; i < cells.length; i++) {
            if (cells[i].innerHTML === '') {
                emptyCells.push(cells[i]);
            }
        }

        // Zvol náhodné volné políčko a zapiš tah AI
        var randomCell = emptyCells[Math.floor(Math.random() * emptyCells.length)];
        randomCell.innerHTML = currentPlayer;

        // Kontrola výhry AI
        if (checkWin()) {
            alert('Hráč ' + currentPlayer + ' vyhrál!');
            endGame();
            return;
        }

        // Kontrola remízy
        if (checkDraw()) {
            alert('Remíza!');
            endGame();
            return;
        }

        // Přepnutí hráče
        switchPlayer();
    }
}

// Funkce pro uložení momentálního stavu pole do souboru
function saveCurrentBoard() {
    var cells = document.querySelectorAll('.board td');
    var currentBoard = [];

    // Uložení stavu políček do pole
    for (var i = 0; i < cells.length; i++) {
        currentBoard.push(cells[i].innerHTML);
    }

    // Uložení do souboru currentBoard.json
    var json = JSON.stringify(currentBoard);
    fetch('currentBoard.json', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: json,
    })
        .then(response => response.json())
        .then(data => {
            console.log('Momentální stav pole uložen:', data);
        })
        .catch(error => {
            console.error('Chyba při ukládání momentálního stavu pole:', error);
        });
}