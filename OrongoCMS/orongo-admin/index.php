<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo('admin_index');

Security::promptAuth();

$index = new AdminFrontend();
$index->main(array("time" => time(), "page_title" => "Dashboard", "page_template" => "dashboard"));

if(isset($_GET['msg'])){
    switch($_GET['msg']){
        case 0:
            $index->addMessage(l("No Permission"), "error");
            break;
        case 1:
            $index->addMessage(l("Invalid Query Arg"), "warning");
            break;
        case 2:
            $index->addMessage(l("Internal Error"), "warning");
            break;
    }
}

$text = "<strong>Thank you for testing OrongoCMS!</strong><br/><br/>";
$text .= "<p>To check for updates go to <a href='" . orongoURL("orongo-admin/orongo-update-check.php") . "'>the update checker</a>.";
$text .= "<br/>Found bugs? Please post them <a href='" . orongoURL("orongo-admin/post-issue.php") . "'>here</a>.";
$text .= "<br/>You can find the terminal of your OrongoCMS installation <a href='" . OrongoURL("orongo-admin/terminal.php") . "'>here</a>.";
$text .= "<br/><br/>Enjoy OrongoCMS,<br/> ";
$text .= "<strong>The OrongoCMS Team</strong>";
$index->addObject(new AdminFrontendObject(100, "Info", $text, null, false));
$index->render();
?>
