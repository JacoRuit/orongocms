<?php
/**
 * @author Jaco Ruit
 */


require '../startOrongo.php';
startOrongo();

if(isset($_COOKIE['auth-sub-token']) || isset($_POST['issue_author']) || isset($_POST['issue_title']) || isset($_POST['issue_labels']) || isset($_POST['issue_content'])){
    $issue = new Issue($_POST['issue_title']);
    $issue->setStatus("New");
    $issue->setAuthor($_POST['issue_author']);
    $issue->setContent($_POST['issue_content']);
    if(!empty($_POST['issue_labels'])){
        $labels = explode(",", trim($_POST['issue_labels']));
        foreach($labels as &$label){
            trim($label);
        }
        $issue->setLabels($labels);
    }
    $issueTracker = new IssueTracker($_COOKIE['auth-sub-token']);
    setcookie ("auth-sub-token", "", time() - 3600);
    try{
        $issueTracker->postIssue($issue);
        header("Location: " . orongoURL("orongo-admin/post-issue.php?msg=0"));
    }catch(Exception $e){
        header("Location: " . orongoURL("orongo-admin/post-issue.php?error=" . $e->getMessage()));
    }
}else{
    header("Location: ". orongoURL("orongo-admin/post-issue.php"));
}

?>