<?php
/**
 * @author Jaco Ruit
 */

require 'globals.php';


if(isset($_SESSION['orongo-id']) && isset($_SESSION['orongo-session-id'])){
   $externID = Security::escapeSQL($_SESSION['orongo-id']);
   $externSession = Security::escapeSQL($_SESSION['orongo-session-id']);
   if(Session::isGoodSessionID($externSession)){
       $sessionUserID = Session::getUserID($externSession);
       if($sessionUserID == $externID){
           try{
               $user = new User($externID);
          }catch(Exception $e){
              if($e->getCode() == USER_NOT_EXIST){
                  header("Location: ../orongo-logout.php");
                  exit;
              }else{
                  header('HTTP/1.1 500 Internal Server Error');
                  exit;
              }
          }  
      }else{
         Session::delete($externSession);
          session_destroy();
          header("Location: ../orongo-logout.php");
          exit;
      }
   }else{
       Session::delete($externSession);
       session_destroy();
       header("Location: ../orongo-logout.php");
       exit;
   }
}else{
    header("Location: ../orongo-login.php");
}

$website_name = Settings::getWebsiteName();

#   content_block_1
$content_block_1 = "<h2>Stats</h2>";
$uCount = User::getUserCount();
$content_block_1 .= "<p>" . $uCount . " registered and activated user";
if($uCount > 1 || $uCount == 0) $content_block_1 .= "s</p>"; else $content_block_1 .= "</p>";
$pCount = Page::getPageCount();
$content_block_1 .= "<p>" . $pCount .= " published page";
if($pCount > 1 || $pCount == 0) $content_block_1 .= "s</p>"; else $content_block_1 .= "</p>";
$sCount = Storage::getStorageCount();
$content_block_1 .= "<p>" . $sCount . " item";
if($sCount > 1 || $sCount == 0) $content_block_1 .= "s"; 
$content_block_1 .= " stored in storage</p>";
$plCount = Plugin::getPluginCount();
$content_block_1 .= "<p>" . $plCount .= " activated plugin";
if($plCount > 1 || $plCount == 0) $content_block_1 .= "s</p>"; else $content_block_1 .= "</p>";

#   Template

$smarty->assign("head_title", $website_name . " - Administration - Logged in as " . $user->getName());
$smarty->assign("website_url", Settings::getWebsiteURL());
$smarty->assign("website_name", $website_name);
$smarty->assign("document_ready", '');
$smarty->assign("content_block_1", $content_block_1);

$smarty->assign("style", "style.interface");
$smarty->assign("username", $user->getName());
$smarty->display("header.orongo");
$smarty->display("index.orongo");
$smarty->display("footer.orongo");

?>
