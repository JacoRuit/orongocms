<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_orongo-settings');

Security::promptAuth();

if(getUser()->getRank() != RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

$settings = new AdminFrontend();

if(isset($_GET['msg'])){
    switch($_GET['msg']){
        case 0:
            $settings->addMessage(l("Settings saved"), "success");
        default:
            break;
    }
}

$settings->main(array('time' => time(), 'page_title' => 'Orongo Settings', 'page_template' => 'dashboard'));


$settingForm = new AdminFrontendForm(100, "Orongo Settings", "POST", orongoURL("actions/action_SaveOrongoSettings.php"));

$settingForm->addInput("Website Name", "website_name", "text", Settings::getWebsiteName());
$settingForm->addInput("Website URL", "website_url", "text", Settings::getWebsiteURL());
$settingForm->addInput("Admin Email", "admin_email", "text", Settings::getEmail());
$currentShowArchiveString = Settings::showArchive() ? l("Yes") : l("No");
$settingForm->addRadios("Show archive", "show_archive", array( 
    l("Yes") => "true",
    l("No") => "false"
), $currentShowArchiveString);
$languages = array(Settings::getLanguageName() => "nl_NL");

$files = @scandir(ADMIN . '/lang/');
if(is_array($files)){
    foreach($files as $file){
        if($file == Settings::getLanguageName() || stristr($file, ".")) continue;
        $languages[$file] = $file;
    }
}
$settingForm->addSelect("website_lang", $languages);
$settingForm->addButton("Save", true);        

$settings->addObject($settingForm);
$settings->render();
?>
