<?php
/**
 * terminal PHPRPC
 * 
 * @author Jaco Ruit
 */

define('ORONGOTERMINAL_VERSION', "0.2.1");
define('JQUERYTERMINAL_VERSION', "0.4.6");
require('globals.php');
require('json-rpc.php');
 
function terminalAuth(){
    $user = handleSessions();
    if($user == null)
        throw new Exception("You're not logged in!");
    if($user->getRank() != RANK_ADMIN)
        throw new Exception("You need to be an admin to access the terminal.");
    return $user;
}

class OrongoTerminal {

  public function cmd(){
      $cmds = array(
          'cmd' => 'show commands', 
          'version' => 'show version numbers', 
          'about'=>'show about', 
          'clear' => 'clear screen', 
          'whoami' => 'show your user info', 
          'get' => 'fetch an object', 
          'orongoquery/oq/oquery' => 'execute an orongoquery', 
          'cout' => 'output up to 5 strings',  
          'time' => 'returns current unix timestamp'
      );
      ksort($cmds, SORT_STRING);
      return $cmds;
  }
  
  public function oq($query = null){
      return $this->orongoquery($query);
  }
  
  public function oquery($query = null){
      return $this->orongoquery($query);
  }
  
  public function about(){
      return "This terminal is using the jQuery terminal plugin from http://terminal.jcubic.pl/\nThe OrongoTerminal is being maintained by Jaco Ruit.";
  }
  
  public function version(){
      return array(
          "OrongoCMS version" => "r" . REVISION,
          "OrongoTerminal version" => "v" . ORONGOTERMINAL_VERSION,
          "PHP version" => "v" . phpversion(),
          "MySQL version" => "v" . mysql_get_server_info(),
          "jQueryTerminal version" => "v" . JQUERYTERMINAL_VERSION
      );
  }
  
  public function get($obj = null, $id = null){
      terminalAuth();
      if($id == null || !is_numeric($id))
          throw new Exception("invalid id");
      if($obj == null || ($obj != 'page' && $obj != 'user' &&$obj  != 'comment' && $obj != 'article'))
          throw new Exception("invalid object");
      $result = orongo_query("action=fetch&object=" . $obj . "&max=1&where=" . $obj . ".id:" . $id);
      foreach($result as $obj){
           if($obj instanceof Article){
                  $arr = array(
                      "article title" => $obj->getTitle(),
                      "article id" => $obj->getID(),
                      "article author name" => $obj->getAuthorName(),
                      "article author id" => $obj->getAuthorID(),
                      "article date" => $obj->getDate(),
                      "article comments count" => $obj->getCommentCount()
                    );
                  return $arr;
             }
             elseif($obj instanceof Comment){
                  $arr = array(
                      "comment id" => $obj->getID(),
                      "comment article id" => $obj->getArticleID(),
                      "comment author name" => $obj->getAuthorName(),
                      "comment author id" => $obj->getAuthorID(),
                      "comment timestamp" => $obj->getTimestamp()
                  );
                  return $arr;
              }
              elseif($obj instanceof Page){
                  $arr = array(
                     "page title" => $obj->getTitle(),
                    "page id" => $obj->getID(),
                  );
                  return $arr;
              }
              elseif($obj instanceof User){
                  $arr = array(
                     "user name" => $obj->getName(),
                     "user id" => $obj->getID(),
                     "user rank" => $obj->getRank(),
                     "user email" => $obj->getEmail()
                  );
                  return $arr;
              }
      }
      throw new Exception("wrong id");
  }
  
  public function orongoquery($query = null){
      terminalAuth();
      if($query == null)
          throw new Exception("No query string.");
      $query = new OrongoQuery($query);
      $result = OrongoQueryHandler::exec($query);
      $return = array();
      $qa = $query->getQueryArray();
      if($qa["action"] == "count")
          return $result;
      else{
          $str = "";
          for($i = 0; $i < count($result) - 1; $i++){
              $obj = $result[$i];
              if($obj instanceof Article){
                  $arr = array(
                      $i . "_" . "article title" => $obj->getTitle(),
                      $i . "_" ."article id" => $obj->getID(),
                      $i . "_" ."article author name" => $obj->getAuthorName(),
                      $i . "_" ."article author id" => $obj->getAuthorID(),
                      $i . "_" ."article date" => $obj->getDate(),
                      $i . "_" ."article comments count" => $obj->getCommentCount()
                    );
                  $return = array_merge($arr, $return);
              }
              elseif($obj instanceof Comment){
                  $arr = array(
                      $i . "_" ."comment id" => $obj->getID(),
                      $i . "_" ."comment author name" => $obj->getAuthorName(),
                      $i . "_" ."comment author id" => $obj->getAuthorID(),
                      $i . "_" ."comment timestamp" => $obj->getTimestamp()
                  );
                  $return = array_merge($arr, $return);
              }
              elseif($obj instanceof Page){
                  $arr = array(
                     $i . "_" ."page title" => $obj->getTitle(),
                     $i . "_" ."page id" => $obj->getID(),
                  );
                  $return = array_merge($arr, $return);
              }
              elseif($obj instanceof User){
                  $arr = array(
                     $i . "_" ."user name" => $obj->getName(),
                     $i . "_" ."user id" => $obj->getID(),
                     $i . "_" ."user rank" => $obj->getRank(),
                     $i . "_" ."user email" => $obj->getEmail()
                  );
                  $return = array_merge($arr, $return);
              }
          }
      }
      return $return;
  }
  
  
  public function time(){
      return strval(time());
  }
  
  public function whoami() {
    $user = terminalAuth();
    return array(
        "user name" => $user->getName(),
        "user id" => $user->getID(),
        "user rank" => $user->getRank(),
        "user email" => $user->getEmail(),
        "session id" => substr($_SESSION['orongo-session-id'],0,25) . "***************",
        "ip" => $_SERVER['REMOTE_ADDR'],
      );
  }
  
  public function plugin($plugin = null,$command = null,$pluginargs = null){
      $user = terminalAuth();
      if($plugin == null || $command == null || $pluginargs = null)
          throw new Exception("You're missing some arguments!");
      var_dump($pluginargs);
  }
  
  public function cout($string, $s2 = "",$s3 = "", $s4 = "", $s5 = ""){
      return $string . " " . $s2 . " " .$s3 . " " . $s4 . " " . $s5;
  }
  
  public function wtf($p = null, $p2 = null, $p3 = null, $p4 = null){
      return "dafuq!";
  }

  
}

class Test{
    public function bla(){
        return "bla";
    }
}
 
handle_json_rpc(array(new OrongoTerminal(), new Test()));
?>
