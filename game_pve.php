<!DOCTYPE html>
<html>
<head>
  <title>PVE Game</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <h1>PVE Game</h1>

  <?php
  require_once 'functions.php';
  $board = generateBoard(10, 10);
  echo $board;
  ?>

  <script src="scriptPVE.js"></script>
</body>
</html>
