<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_orongo-update-check');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

$updater = new AdminFrontend();
$updater->main(array("time" => time(), "page_title" => "Update Checker", "page_template" => "dashboard"));

$isUpdateAvailable = false;
try{
    $isUpdateAvailable = OrongoUpdateChecker::isUpdateAvailable();
}catch(Exception $e){
    $msgbox = new MessageBox(l("Error update check"));
    $msgbox->bindException($e);
    getDisplay()->addObject($msgbox);
}

if($isUpdateAvailable){
    $updater->addMessage(l("Update available"), "success");
    $info = null;
    try{
        $info = OrongoUpdateChecker::getLatestVersionInfo();
    }catch(Exception $e){
        $msgbox = new MessageBox(l("Error update check"));
        $msgbox->bindException($e);
        getDisplay()->addObject($msgbox);
        break;
    }
    $updater->addObject(new AdminFrontendObject(100, "How to update", l("Ready to update to", $info['latest_version']) . '<br/>' . l("Visit website for update information", $info['update_url'])));
}else
    $updater->addMessage(l("No update"), "success");

$updater->render();

?>
