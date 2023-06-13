<!DOCTYPE html>
<html>
<head>
  <title>PVP Game</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <h1>PVP Game</h1>
  <div class="center">
    <?php
    require_once 'functions.php';
    $board = generateBoard(10, 10);
    echo $board;
    ?>

    <br /><br />
    <a href="index.php">Hlavní stránka</a>
  </div>

  <script src="scriptPVP.js"></script>
</body>
</html>
