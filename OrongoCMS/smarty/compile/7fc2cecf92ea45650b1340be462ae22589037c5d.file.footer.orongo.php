<?php /* Smarty version Smarty 3.1.4, created on 2012-01-03 17:35:00
         compiled from "themes/monk\footer.orongo" */ ?>
<?php /*%%SmartyHeaderCode:192484f032de9a23bc8-93281680%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7fc2cecf92ea45650b1340be462ae22589037c5d' => 
    array (
      0 => 'themes/monk\\footer.orongo',
      1 => 1325608498,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '192484f032de9a23bc8-93281680',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4f032de9a5c6d',
  'variables' => 
  array (
    'footer_1_title' => 0,
    'footer_1_text' => 0,
    'footer_2_title' => 0,
    'footer_2_text' => 0,
    'footer_3_title' => 0,
    'footer_3_text' => 0,
    'footer_4_title' => 0,
    'footer_4_text' => 0,
    'website_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f032de9a5c6d')) {function content_4f032de9a5c6d($_smarty_tpl) {?>	<div id="footer-main"><!--start footer-main-->
		<div class="one_fourth">
			<h3><?php echo $_smarty_tpl->tpl_vars['footer_1_title']->value;?>
</h3>
			<p><?php echo $_smarty_tpl->tpl_vars['footer_1_text']->value;?>
</p>
		</div>
		<div class="one_fourth">
			<h3><?php echo $_smarty_tpl->tpl_vars['footer_2_title']->value;?>
</h3>
			<p><?php echo $_smarty_tpl->tpl_vars['footer_2_text']->value;?>
</p>
		</div>
		<div class="one_fourth">
			<h3><?php echo $_smarty_tpl->tpl_vars['footer_3_title']->value;?>
</h3>
			<p><?php echo $_smarty_tpl->tpl_vars['footer_3_text']->value;?>
</p>
		</div>    
		<div class="one_fourth column-last">
			<h3><?php echo $_smarty_tpl->tpl_vars['footer_4_title']->value;?>
</h3>
			<p><?php echo $_smarty_tpl->tpl_vars['footer_4_text']->value;?>
</p>
		</div>
	</div><!--end footer-main-->

	<div id="footer">
		<p id="footer-text">&copy; Copyright 2011 <a href="http://www.makimyers.co.uk/">Monk</a> by
		<a href="http://www.makimyers.co.uk">Maki Myers</a> - Orongo'd by Jaco Ruit ~ Proudly powered by <a href="http://www.orongocms.eu/">OrongoCMS</a></p>
	</div>

</div><!--end-body wrapper-->

    <!--Nivo Slider-->
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
themes/monk/js/slide/jquery.nivo.slider.pack.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
    </script>
	<!--Nivo Slider-->
	
</body>
</html>
        <?php }} ?>