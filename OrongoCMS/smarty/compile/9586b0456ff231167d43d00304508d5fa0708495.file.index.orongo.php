<?php /* Smarty version Smarty 3.1.4, created on 2012-01-03 17:40:26
         compiled from "themes/monk\index.orongo" */ ?>
<?php /*%%SmartyHeaderCode:188284f031300df86f9-71264689%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9586b0456ff231167d43d00304508d5fa0708495' => 
    array (
      0 => 'themes/monk\\index.orongo',
      1 => 1325608823,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '188284f031300df86f9-71264689',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4f031300eb018',
  'variables' => 
  array (
    'website_url' => 0,
    'document_ready' => 0,
    'plugin_document_ready' => 0,
    'menu_bar' => 0,
    'plugin_body' => 0,
    'errors' => 0,
    'logo_url' => 0,
    'menu' => 0,
    'articles' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f031300eb018')) {function content_4f031300eb018($_smarty_tpl) {?><body>
<script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/widget.prettyAlert.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
        $(document).ready(function(){
            <?php echo $_smarty_tpl->tpl_vars['document_ready']->value;?>

            <?php echo $_smarty_tpl->tpl_vars['plugin_document_ready']->value;?>

        });
</script>
<?php echo $_smarty_tpl->tpl_vars['menu_bar']->value;?>

<?php echo $_smarty_tpl->tpl_vars['plugin_body']->value;?>

<?php echo $_smarty_tpl->tpl_vars['errors']->value;?>

<div class="header">
	<div id="logo">
		<a href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
">
		<img alt="logo" src="<?php echo $_smarty_tpl->tpl_vars['logo_url']->value;?>
">
		</a>
	</div>

	<ul id="menu" class="menu">
		<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>

	</ul>
</div>

<div id="body-wrapper" class="clearfix"><!--start body-wrapper-->
<div class="clear"></div>
	<div style="padding-top:40px;" class="slider-wrapper theme-default">
		<div id="slider" class="nivoSlider">
            <img src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/slide1.png" title="This is a caption!" alt="slider image 1"/>
			<img src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/slide2.png" title="Want to change the images? Open up index.orongo in the monk folder located in the themes folder." alt="slider image 2"/>
		</div>
    </div>

	<div class="box">
		<h1>Latest Articles</h1>
    </div>

<!-- images above articles -->
                    <!-- you can simply delete this or keep it :D -->
	<div class="one_fourth"><!--start top half content-->
		<div class="fancy"><a id="fancybox" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/210-150-2.png"><img alt="fancybox" src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/210-150-2.png" /></a></div>
	</div>
	<div class="one_fourth">
		<div class="fancy"><a id="fancybox" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/210-150-1.png"><img alt="fancybox" src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/210-150-1.png" /></a></div>
	</div>
	<div class="one_fourth">
		<div class="fancy"><a id="fancybox" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/210-150-2.png"><img alt="fancybox" src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/210-150-2.png" /></a></div>
	</div>
	<div class="one_fourth column-last">
		<div class="fancy"><a id="fancybox" href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/210-150-3.png"><img alt="fancybox" src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/images/210-150-3.png" /></a></div>
	</div><!--end top half content-->

	<div class="clear"></div>
<!-- end images above articles -->


	<?php echo $_smarty_tpl->tpl_vars['articles']->value;?>

    
    <div class="clear"></div>

<?php }} ?>