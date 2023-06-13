<!DOCTYPE html>
<html>
<head>
  <title>PVE Game</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <h1>PVE Game</h1>
  <div class="center">
    <?php
    require_once 'functions.php';
    $board = generateBoard(10, 10);
    echo $board;
    ?>

    <br /><br />
    <a href="index.php">Hlavní stránka</a>
  </div>


  <script src="scriptPVE.js"></script>
</body>
</html>
