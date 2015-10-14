<?php

function request($args) {
  $data = json_encode($args) . "\n";
  $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

  socket_set_nonblock($socket);

  $timeout = 5000; // 5 seconds

  $error = NULL;
  $connected = FALSE;
  while (!($connected = @socket_connect($socket, "192.168.10.17", "19445")) && ($attempts++ < $timeout)) {
    $error = socket_last_error();
    if ($error != SOCKET_EINPROGRESS && $error != SOCKET_EALREADY) {
      echo "Error Connecting Socket: ".socket_strerror($error) . "\n";
      socket_close($socket);
      //return NULL;
    }
    usleep(1000);
  }

  if (!$connected) {
    echo "Error Connecting Socket: Connect Timed Out After " . $timeout/1000 . " seconds. ".socket_strerror(socket_last_error()) . "\n";
    socket_close($socket);
    //return NULL;
  }

 //echo "start";
 // socket_connect($socket, "192.168.10.17", 19445);
 //echo "end";
  while(!socket_connect($socket, "192.168.10.17", 19445)) {
    echo ".";
  //  if ((time() - $startTime ) >= $timeout) {
  //    die('timeout');
  //  }
    exit;
  }

  if (socket_write($socket, $data) === false) {
    echo "socket_connect() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error($socket)) . "\n";
  }

  $result = "";


    $time = time();
    while ($r = @socket_read($socket, 4096, PHP_NORMAL_READ)) {
      echo ".";
      $result .= $r;
      if(strpos($r, "\n") || $time > 5) break;
      sleep(1);
    }


  socket_close($socket);

  $result = json_decode(trim($result));
  if ($result->success) {
    return $result;
  } else {
    return false;
  }
}

//print_r(request(array("command" => "serverinfo")));
print_r(request(array("command" => "clearall")));
//print_r(request(array("command" => "serverinfo")));
//print_r(request(array("command" => "serverinfo")));
//print_r(request(array("command" => "color", "priority" => 100, "color" => [0,0, 255])));
