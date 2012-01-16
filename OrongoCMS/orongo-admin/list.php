<?php
/**
 * @author Jaco Ruit
 */

require 'globals.php';

$queryString = trim($_SERVER['QUERY_STRING']);
$errors = "";
$website_name = Settings::getWebsiteName();

if(empty($queryString)){
    $msgbox = new MessageBox("No OrongoQuery string!");
    $errors .= $msgbox->toHTML();
    goto end;
}

$orongoQuery = null;
$result = null;

try{
    $orongoQuery = new OrongoQuery($queryString);
    $result = OrongoQueryHandler::exec($orongoQuery);
}catch(Exception $e){
    $msgbox = new MessageBox("Couldn't execute the OrongoQuery.");
    $msgbox->bindException($e);
    $errors .= $msgbox->toHTML();
    goto end;
}

end:
$smarty->assign("head_title", $website_name . " - Administration - Logged in as " . getUser()->getName());
$smarty->assign("website_url", Settings::getWebsiteURL());
$smarty->assign("website_name", $website_name);
$smarty->assign("style", "style.interface");
$smarty->assign("document_ready", '');

$mb = new MenuBar(getUser());
$smarty->assign("menu_bar", $mb->toHTML());
$smarty->assign("errors", $errors);

$smarty->assign("user", getUser());

$smarty->display("header.orongo");
$smarty->assign("inner", "inner_list");
$smarty->display("interface.orongo");
$smarty->display("footer.orongo");


?>
