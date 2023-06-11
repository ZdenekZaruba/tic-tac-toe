<!DOCTYPE html>
<html>
<head>
  <title>PVP Game</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <h1>PVP Game</h1>

  <?php
  require_once 'functions.php';
  $board = generateBoard(10, 10);
  echo $board;
  ?>

  <script src="scriptPVP.js"></script>
</body>
</html>
