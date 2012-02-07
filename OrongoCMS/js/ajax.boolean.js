/*
 * Orongo Javascript
 * ************************************
 * 
 * jQuery function
 * 
 * Last edit: 07-02-2012 by Jaco Ruit
 */

var returned;
function getAjaxBool(turl){
    $.ajax({
       type: 'GET',
       url: turl,
       dataType: 'json',
       success: function(data, textStatus, jqXHR){
           returned = data.bool;
       },
       error: function(jqXHR, textStatus, errorThrown){
       }
    });
    return returned;
};