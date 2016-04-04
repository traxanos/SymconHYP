<?
class Hyperion extends IPSModule {

  public function Create() {
    parent::Create();
    $this->RegisterPropertyString("Host", "");
    $this->RegisterPropertyInteger("Port", 19444);
    $this->RegisterPropertyInteger("Priority", 100);
  }

  public function ApplyChanges() {
    parent::ApplyChanges();

    $script = '<'.'? HYP_Clear('. $this->InstanceID . ', false); ?'.'>';
    $clearId = $this->RegisterScript("CLEAR", "Reset", $script, 1);
    IPS_SetScriptContent($clearId, $script);

    $script = '<'.'? HYP_SetColor('. $this->InstanceID . ', 0, 0, 0); ?'.'>';
    $blackId = $this->RegisterScript("BLACK", "Ausschalten", $script, 2);
    IPS_SetScriptContent($blackId, $script);

    $colorId = $this->RegisterVariableInteger("COLOR", "Farbe", "~HexColor", 3);
    $this->EnableAction("COLOR");
  }

  public function RequestAction($key, $value) {
    switch ($key) {
      case 'COLOR':
         SetValueInteger(IPS_GetObjectIDByIdent('COLOR', $this->InstanceID), $value);
         $value = str_pad(decbin($value), 24,'0', STR_PAD_LEFT);
         $r = bindec(substr($value, 0, 8));
         $g = bindec(substr($value, 8, 8));
         $b = bindec(substr($value, 16, 8));
         $this->SetColor($r, $g, $b);
         break;
    }
  }

  public function RequestData() {
    // Todo
    return $data;
  }

  public function Request($args) {
    $host = $this->ReadPropertyString('Host');
    $port = $this->ReadPropertyInteger('Port');
    $priority = $this->ReadPropertyInteger('Priority');

    if ($host == '') {
      $this->SetStatus(103);
      return false;
    }

    if ($args['command'] != 'clearall') $args['priority'] = $priority;
    $data = json_encode($args) . "\n";
    $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    if (@socket_connect($socket, $host, $port) === false) {
      $this->SetStatus(201);
      socket_close($socket);
      return false;
    }

    if (@socket_write($socket, $data) === false) {
      $this->SetStatus(201);
      socket_close($socket);
      return false;
    }

    $result = "";
    while ($r = @socket_read($socket, 4096, PHP_NORMAL_READ)) {
      $result .= $r;
      if(strpos($r, "\n")) break;
    }
    socket_close($socket);

    $result = json_decode(trim($result));
    if ($result->success) {
      $this->SetStatus(102);
      return $result;
    } else {
      $this->SetStatus(201);
      return false;
    }
  }

  public function SetColor($r, $g, $b) {
    return $this->Request(array('command' => 'color', 'color' => [$r, $g, $b]));
  }

  public function Clear($all = false) {
    $cmd = $all ? 'clearall' : 'clear';
    return $this->Request(array('command' => $cmd));
  }

}
