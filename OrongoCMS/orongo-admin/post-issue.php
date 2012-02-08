<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_post-issue');

Security::promptAuth();

if(getUser()->getRank() < RANK_ADMIN){ header("Location: " . orongoURL("orongo-admin/index.php?msg=0")); exit; }

$postIssue = new AdminFrontend();
getDisplay()->addHTML('<script src="' . orongoURL("js/ajax.boolean.js") . '" type="text/javascript"></script>');
if(isset($_GET['token'])){
    $_SESSION["auth-sub-token"] = $_GET['token'];
    getDisplay()->closeWindow();
    exit;
}
if(!isset($_SESSION["auth-sub-token"])){
    $postIssue->main(array("time" => time(), "page_title" => "Login to Google", "page_template" => "dashboard"));
    $postIssue->addObject(new AdminFrontendObject(100, "Logging in to Google", l("Waiting for login") . "<br/><br/><br/><strong>". l("Do not see popup") . "</strong><br/>" . l("Enable popups") ));
    
    $js = 'window.setInterval(function() {';
    $js .= 'if(getAjaxBool("' . orongoURL("ajax/isGCSet.php") . '")) window.location="' . orongoURL("orongo-admin/post-issue.php") . '"; ';
    $js .= '},2000);';
    getDisplay()->addJS($js, "document.ready");
    
    if(isset($_GET['error'])) $postIssue->addMessage($_GET['error'], "error");
    if(isset($_GET['msg'])){
        switch($_GET['msg']){
            case 0:
                $postIssue->addMessage(l("Issue posted"), "success");
                break;
            default:
                break;
        }
    }else{
        $windowJS = "var login = window.open('" . IssueTracker::getAuthSubRequestUrl(orongoURL("orongo-admin/post-issue.php")) . "');";
        getDisplay()->addJS($windowJS, "document.ready");
    }
    $postIssue->render();
}else{
    $postIssue->main(array("time" => time(), "page_title" => "Post Issue", "page_template" => "dashboard"));
    $form = new AdminFrontendForm(100, "Post Issue", "POST", orongoURL("actions/action_PostIssue.php"));
    $form->addInput("Issue Author", "issue_author", "text", "", true);
    $form->addInput("Issue Title", "issue_title", "text", "", true);
    $form->addInput("Issue Description", "issue_content", "textarea", "",true);
    $form->addInput("Issue Labels", "issue_labels", "text", "");
    $form->addButton("Post", true);
    $postIssue->addObject($form);
    $postIssue->render();
}
?>
