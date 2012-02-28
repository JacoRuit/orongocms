<?php
/**
 * Using the great terminal jQuery plugin: http://terminal.jcubic.pl. Also credits for the guy(s) who made it, it rocks!
 * @author Jaco Ruit
 */
require '../startOrongo.php';
startOrongo();

setCurrentPage('admin_terminal');

Security::promptAuth();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title>OrongoTerminal</title>
        <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="<?php echo Settings::getWebsiteURL(); ?>js/jquery.mousewheel-min.js"></script>
        <script src="<?php echo Settings::getWebsiteURL(); ?>js/jquery.terminal-0.4.6.min.js"></script>
        <link href="<?php echo Settings::getWebsiteURL(); ?>orongo-admin/style/jquery.terminal.css" rel="stylesheet"/>
        <script>
            jQuery(document).ready(function($) {
                $(document.documentElement).terminal("<?php echo Settings::getWebsiteURL(); ?>ajax/terminalRPC.php", { greetings: "Welcome to the OrongoTerminal\n*****************************\n\nTo show commands enter 'cmd'\n\n"});
                $('body').css('display', 'none');
            });
        </script>
    </head>
    <body><noscript>Activate JavaScript</noscript>
    </body>
</html>