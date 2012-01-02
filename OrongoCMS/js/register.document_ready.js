/*
 * Orongo Javascript
 * ************************************
 * 
 * jQuery document.ready function for:
 * 
 * orongo-admin/style/register.orongo
 * 
 * 
 * Last edit: 12-12-2011 by Jaco Ruit
 */
$(document).ready(function() {
    
    var passwordField = $('input[name=password]');
    passwordField.after('<input class="login_input validate[required,minSize[6]]" name="password_ph"  id="password_ph" type="text" value="Password" />');
    var passwordPlaceholder = $('#password_ph');
    var passwordAgainField = $('input[name=password_again]');
    passwordAgainField.after('<input class="login_input validate[required,checkPassword[password],required]]" name="password_again_ph" id="password_again_ph" type="text" value="Password again" />');
    var passwordAgainPlaceholder = $('#password_again_ph');
    var usernameField = $('input[name=username]');
    var emailField = $('input[name=email]');

    passwordPlaceholder.show();
    passwordField.hide();
    passwordAgainPlaceholder.show();
    passwordAgainField.hide();

    passwordPlaceholder.focus(function() {
        
        passwordPlaceholder.hide();
        passwordField.show();
        passwordField.focus();
        $("#register_form").validationEngine('attach');
    });
            
    passwordAgainPlaceholder.focus(function() {
        passwordAgainPlaceholder.hide();
        passwordAgainField.show();
        passwordAgainField.focus();
        $("#register_form").validationEngine('attach');
    });

    passwordField.blur(function() {
       if(passwordField.val() == '') {
           passwordPlaceholder.show();
           passwordField.hide();
       }
       $("#register_form").validationEngine('attach');
    });

    passwordAgainField.blur(function() {  
       if(passwordAgainField.val() == '') {
           passwordAgainPlaceholder.show();
           passwordAgainField.hide();
       }
       $("#register_form").validationEngine('attach');
    });

             
    usernameField.focus(function() {
        if(usernameField.val() == 'Username')
            usernameField.val('');
    });
            
    usernameField.blur(function() {
        if(usernameField.val() == '')
            usernameField.val('Username');
    });
            
    emailField.focus(function() {
        if(emailField.val() == 'Email Address')
             emailField.val('');
   });
            
    emailField.blur(function() {
        if(emailField.val() == '')
              emailField.val('Email Address');
    }); 
    
});