<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_index');

Security::promptAuth();

$index = new AdminFrontend();
$index->main(array("time" => time(), "page" => "index"));
$index->render();
?>
