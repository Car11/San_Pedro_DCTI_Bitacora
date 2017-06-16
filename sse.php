<?php
function sendMsg($msg) {
  echo "data: $msg" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
  print "!!si!";
}
  
?>


