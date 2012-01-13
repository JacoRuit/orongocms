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

//Load plugins
Plugin::getActivatedPlugins('../orongo-admin/');

//Get loaded terminal plugins
$plugins = Plugin::getHookedTerminalPlugins();

class GeneralCommands{
    public function about(){
      return "This terminal is using the jQuery terminal plugin from http://terminal.jcubic.pl/\nThe OrongoTerminal is being maintained by Jaco Ruit.";
  }
  
  public function version(){
      $plugins = Plugin::getHookedTerminalPlugins();
      $versions = array(
          "OrongoCMS version" => "r" . REVISION,
          "OrongoTerminal version" => "v" . ORONGOTERMINAL_VERSION,
          "jQueryTerminal version" => "v" . JQUERYTERMINAL_VERSION,
          "\n" => "",
          "PHP version" => "v" . phpversion(),
          "MySQL version" => "v" . mysql_get_server_info(),
          "\n" => ""
      );
      $pluginversions = array();
      foreach($plugins as $plugin){
          $pluginName = get_class($plugin);
          if(array_key_exists($pluginName, $pluginversions)) continue;
          $vn = -1;
          try{
              $vn = $plugin->getVersionNumber();
          }catch(Exception $e){ continue; }
          $pluginversions[$pluginName] = $vn;
      }
      $allversions = $versions + $pluginversions;
      $str = "";
      foreach($allversions as $key=>$version){
          if($key == "\n")
              $str .= $key . $version;
          else{
              $str .=  $key . ": " . $version . "\n";
          }
      }
      return $str;
  }
}
$objs = array(new OrongoTerminal(), new GeneralCommands());
$objs = array_merge($plugins, $objs);
handle_json_rpc($objs);
?>
