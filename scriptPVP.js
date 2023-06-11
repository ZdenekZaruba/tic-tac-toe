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
            restartGame();
            return;
        }

        // Kontrola remízy
        if (checkDraw()) {
            alert('Remíza!');
            restartGame();
            return;
        }

        // Přepnutí hráče
        switchPlayer();
    }
});

// Funkce pro přepnutí hráče
function switchPlayer() {
    currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
}

// Funkce pro kontrolu výhry
function checkWin() {
    var cells = document.querySelectorAll('.board td');

    // Kontrola horizontálních řádků
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

    // Kontrola vertikálních sloupců
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

    // Kontrola diagonál zleva doprava
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

    // Kontrola diagonál zprava doleva
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

// Funkce pro restart hry a vyprázdnění pole
function restartGame() {
    // Vyprázdnění obsahu buněk
    var cells = document.querySelectorAll('.board td');
    for (var i = 0; i < cells.length; i++) {
        cells[i].innerHTML = '';
    }

    // Resetování globálních proměnných
    currentPlayer = 'X';
    gameOver = false;
}

// Odkliknutí popupu
document.getElementById('popup').addEventListener('click', function () {
    restartGame();
});

function callPHPScript() {
    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "history.php", true);
    xhttp.send();
}

//kod pro dokonceni hry

//volani funkce pro zavolani PHP skriptu
callPHPScript();
