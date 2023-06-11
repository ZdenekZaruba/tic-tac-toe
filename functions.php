<?php
// Generování herního pole
$board = generateBoard(10, 10);

function generateBoard($rows, $cols) {
  $board = '<table class="board">';

  for ($i = 0; $i < $rows; $i++) {
    $board .= '<tr>';
    for ($j = 0; $j < $cols; $j++) {
      $board .= '<td></td>';
    }
    $board .= '</tr>';
  }

  $board .= '</table>';

  return $board;
}
?>
