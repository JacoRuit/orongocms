<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_post-issue');

Security::promptAuth();

$postIssue = new AdminFrontend();

if(isset($_GET['token'])){
    setcookie("auth-sub-token", $_GET['token'], time() + 1000);
    $_GET['token'] = null;
    getDisplay()->closeWindow();
    exit;
}
if(!isset($_COOKIE['auth-sub-token'])){
    $postIssue->main(array("time" => time(), "page_title" => "Login to Google", "page_template" => "dashboard"));
   // $postIssue->addObject(new AdminFrontendObject(100, "Login to Google", "<iframe style='width:100%; height:100%;' scrolling='yes' src=\"" . IssueTracker::getAuthSubRequestUrl(orongoURL("orongo-admin/post-issue.php")) . "\"></iframe>"));
    
    $windowJS = "var login = window.open('" . IssueTracker::getAuthSubRequestUrl(orongoURL("orongo-admin/post-issue.php")) . "');";
    
    getDisplay()->addJS($windowJS, "document.ready");

    $postIssue->addObject(new AdminFrontendObject(100, "Logging in to Google", "We're waiting for you to login.<br/><br/><br/><strong>Didn't see the popup window?</strong><br/>Please enable popups and refresh the page." ));
    
    $js = 'setTimeout("cookieCheck()", 1000); ';
    $js .= 'function cookieCheck(){ ';
    $js .= '  if($.cookie("auth-sub-token") != null) ';
    $js .= '  window.location("' . orongoURL('orongo-admin/post-issue.php') . '"); ';
    $js .= '}';
    getDisplay()->addJS($js, "document.ready");
    
    if(isset($_GET['error'])) $postIssue->addMessage($_GET['error'], "error");
    if(isset($_GET['msg'])){
        switch($_GET['msg']){
            case 0:
                $postIssue->addMessage("Issue posted, thanks!", "success");
                break;
            default:
                break;
        }
    }
    $postIssue->render();
}else{
    $postIssue->main(array("time" => time(), "page_title" => "Post Issue", "page_template" => "dashboard"));
    $form = new AdminFrontendForm(100, "Post Issue", "POST", orongoURL("actions/action_PostIssue.php"));
    $form->addInput("Issue Author", "issue_author", "text", "", true);
    $form->addInput("Issue Title", "issue_title", "text", "", true);
    $form->addInput("Issue Description", "issue_content", "textarea", "",true);
    $form->addInput("Issue Labels", "issue_labels", "text", "label1, label2");
    $form->addButton("Post", true);
    $postIssue->addObject($form);
    if(isset($_GET['error'])) $postIssue->addMessage($_GET['error'], "error");
    if(isset($_GET['msg'])){
        switch($_GET['msg']){
            case 0:
                $postIssue->addMessage("Issue posted, thanks!", "success");
                break;
            default:
                break;
        }
    }
    $postIssue->render();
}
?>
