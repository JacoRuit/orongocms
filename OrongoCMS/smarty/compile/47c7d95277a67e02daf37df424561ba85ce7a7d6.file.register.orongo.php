<?php /* Smarty version Smarty 3.1.4, created on 2011-12-16 10:37:32
         compiled from "orongo-admin/style\register.orongo" */ ?>
<?php /*%%SmartyHeaderCode:80384ee8c428207909-98802474%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '47c7d95277a67e02daf37df424561ba85ce7a7d6' => 
    array (
      0 => 'orongo-admin/style\\register.orongo',
      1 => 1323965399,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '80384ee8c428207909-98802474',
  'function' => 
  array (
  ),
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_4ee8c4282b5b9',
  'variables' => 
  array (
    'website_url' => 0,
    'document_ready' => 0,
    'website_name' => 0,
    'register_msg' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4ee8c4282b5b9')) {function content_4ee8c4282b5b9($_smarty_tpl) {?>    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/widget.prettyAlert.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".register").fadeIn(700);
            $(".logowrap").fadeIn(2000); 
            <?php echo $_smarty_tpl->tpl_vars['document_ready']->value;?>

        });
    </script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/interface.menu_effects.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
js/register.document_ready.js" type="text/javascript" charset="utf-8"></script>
    <div class="menu hide fixed">
        <div class="menu_text left hide" style="padding-left: 200px">
            <div class="icon_account_small left"></div> Not logged in | <a href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
">Back to <?php echo $_smarty_tpl->tpl_vars['website_name']->value;?>
</a>
        </div>
    </div>
    <div class="logowrap">
	<a href="($website_url}orongo-login.php"><img class="logo" src="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-admin/style/images/logo.png" alt="orongo"/></a>
    </div>
    <?php echo $_smarty_tpl->tpl_vars['register_msg']->value;?>

    <div class="register">
	<p><?php echo $_smarty_tpl->tpl_vars['website_name']->value;?>
 - Register</p>
        <form id="register_form" method="post" action="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
actions/action_Register.php">
            <input class="login_input validate[required,custom[onlyLetterNumber],minSize[4],maxSize[20],ajax[usernameExist]] text-input" type="text" name="username" id="username" value="Username" /><br /><br />
            <input class="login_input validate[required,minSize[6]] text-input" type="password" name="password" id="password" /><br /><br />
            <input class="login_input validate[checkPassword[password],required] text-input" type="password" name="password_again" id="password_again" /><br /><br />
            <input class="login_input validate[required,minSize[4], emailValidation] text-input" type="text" name="email" id="email" value="Email Address" /><br /><br />
            <input  class="login_register" type="submit" value="Register" id="register_button" /><br /><br />
        </form>
    </div>
    <div class="info">
        <p><a href="#">Forgot Password?</a> | <a href="<?php echo $_smarty_tpl->tpl_vars['website_url']->value;?>
orongo-login.php">Login</a></p>
    </div><?php }} ?>