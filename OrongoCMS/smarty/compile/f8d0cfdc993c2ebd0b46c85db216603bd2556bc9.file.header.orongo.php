<?php /* Smarty version Smarty 3.1.4, created on 2012-01-03 15:42:12
         compiled from "themes/monk\header.orongo" */ ?>
<?php /*%%SmartyHeaderCode:119674f031300ae2659-68215770%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f8d0cfdc993c2ebd0b46c85db216603bd2556bc9' => 
    array (
      0 => 'themes/monk\\header.orongo',
      1 => 1325601730,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '119674f031300ae2659-68215770',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4f031300d5ad6',
  'variables' => 
  array (
    'head_title' => 0,
    'website_url' => 0,
    'head' => 0,
    'plugin_head' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f031300d5ad6')) {function content_4f031300d5ad6($_smarty_tpl) {?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $_smarty_tpl->tpl_vars['head_title']->value;?>
</title>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/style.css" type="text/css" media="screen"/>

<!--Nivo Slider-->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/js/slide/default.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/js/slide/nivo-slider.css" type="text/css" media="screen" />
<!--Nivo Slider-->

<!--FANCY BOX-->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
	<script>
		!window.jQuery && document.write('<script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/js/jquery-1.4.3.min.js"><\/script>');
	</script>
	<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {
			$("a#fancybox").fancybox();	 
		});
	</script>
	<style>
	.fancy a img {
	border: 1px solid #BBB;
	padding: 2px;
	margin: 10px 20px 10px 0;
	vertical-align: top;
	}
	.fancy a img.last {
	margin-right: 0;	
	}
	</style>
<!--FANCY BOX-->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>     
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-admin/style/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
        <?php echo $_smarty_tpl->tpl_vars['head']->value;?>

        <?php echo $_smarty_tpl->tpl_vars['plugin_head']->value;?>

</head>

<?php }} ?>