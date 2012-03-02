<?php
/**
 * @author Jaco Ruit
 */
require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_orongo-settings');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

if(!isset($_POST['website_url']) || !isset($_POST['website_style']) || !isset($_POST['website_name'])  || !isset($_POST['website_lang']) || !isset($_POST['show_archive'])){
   header("Location: ". orongoURL("orongo-admin/orongo-settings.php"));
   exit;
}

if(Settings::getWebsiteURL() != $_POST['website_url']&& !empty($_POST['website_url']))
    Settings::setWebsiteURL($_POST['website_url']);
if(Settings::getWebsiteName() != $_POST['website_name'] && !empty($_POST['website_name']))
    Settings::setWebsiteName($_POST['website_name'] );
if(Settings::getLanguageName() != $_POST['website_lang'] && !empty($_POST['website_lang']))
    Settings::setLanguageName($_POST['website_lang']);
if(strval(Settings::showArchive()) != $_POST['show_archive'] && !empty($_POST['show_archive']))
    Settings::setShowArchive($_POST['show_archive']);
if(getStyle()->getStyleFolder() != $_POST['website_style'] && file_exists(ROOT . "/themes/" . $_POST['website_style']) . "/info.xml"){
    try{
        Settings::setStyle($_POST['website_style']);
    }catch(Exception $e){
        $msgbox = new MessageBox("Can't install style: " . $_POST['website_style']);
        $msgbox->bindException($e);
        die($msgbox->getImports() . $msgbox->toHTML());
    }
}
    
header("Location: " . orongoURL("orongo-admin/orongo-settings.php?msg=0"));
exit;
?>
