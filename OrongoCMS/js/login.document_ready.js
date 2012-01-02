/*
 * Orongo Javascript
 * ************************************
 * 
 * jQuery document.ready function for:
 * 
 * orongo-admin/style/login.orongo
 * 
 * 
 * Last edit: 12-12-2011 by Jaco Ruit
 */
$(document).ready(function() {

    var usernameField = $('input[name=username]');
    var passwordField = $('input[name=password]');
    passwordField.after('<input class="login_input" name="password_ph"  id="password_ph" type="text" value="Password" />');
    var passwordPlaceholder = $('#password_ph');
    
    passwordPlaceholder.show();
    passwordField.hide();
    
    passwordPlaceholder.focus(function() {
        passwordPlaceholder.hide();
        passwordField.show();
        passwordField.focus();
    });
            
    passwordField.blur(function() {
       if(passwordField.val() == '') {
           passwordPlaceholder.show();
           passwordField.hide();
       }
    });
    
    usernameField.focus(function() {
        if(usernameField.val() == 'Username')
            usernameField.val('');
    });
            
    usernameField.blur(function() {
        if(usernameField.val() == '')
            usernameField.val('Username');
    });
    
});