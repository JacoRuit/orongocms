<?php
/**
 * terminal PHPRPC
 * 
 * @author Jaco Ruit
 */
require('globals.php');
require('json-rpc.php');
 
function auth(){
    $user = handleSessions();
    if($user == null)
        throw new Exception("You're not logged in!");
    if($user->getRank() != RANK_ADMIN)
        throw new Exception("You need to be an admin to access the terminal.");
    return $user;
}

class OrongoTerminal {

  public function cmd(){
      return array('cmd' => 'show commands', 'orongoquery/oq/oquery' => 'execute an orongoquery' , 'time' => 'returns current unix timestamp');
  }
  
  public function oq($query = null){
      $this->orongoquery($query);
  }
  
  public function oquery($query = null){
      $this->orongoquery($query);
  }
  
  public function orongoquery($query = null){
      if($query == null)
          throw new Exception("No query string.");
      $query = new OrongoQuery($query);
      $result = OrongoQueryHandler::exec($query);
      $qa = $query->getQueryArray();
      if($qa["action"] == "count")
          return $result;
      else{
          $str = "";
          foreach($result as $obj){
              if($obj instanceof Article){
                  
              }
              elseif($obj instanceof Comment){
                  
              }
              elseif($obj instanceof Page){
                  
              }
              elseif($obj instanceof User){
                  
              }
          }
      }
  }
  
  public function __call($name, $arguments) {
      return "boo " + $name;
  }
  
  public function time(){
      $ts = time();
      return $ts;
  }
  
  public function whoami() {
    $user = auth();
    return array(
        "username" => $user->getName(),
        "user id" => $user->getID(),
        "user rank" => $user->getRank(),
        "user email" => $user->getEmail(),
        "session id" => $_SESSION['orongo-session-id'],
        "ip" => $_SERVER['REMOTE_ADDR'],
      );
  }
}
 
handle_json_rpc(new OrongoTerminal());
?>
