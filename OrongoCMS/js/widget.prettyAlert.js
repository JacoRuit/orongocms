/*
 * Orongo Javascript
 * ************************************
 * 
 * jQuery widget
 * 
 * Note:
 *  To use this, import jQuery UI libs first.
 * 
 * Last edit: 12-12-2011 by Jaco Ruit
 */

function prettyAlert(div, message, title){
    $(div).text(message).dialog({
	modal:true,
	title: title,
	buttons: {
	    'OK':function(){
                    $(this).dialog('close');
            }
	}
    });
};