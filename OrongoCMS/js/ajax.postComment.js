/*
 * Orongo Javascript
 * ************************************
 * 
 * jQuery function
 * 
 * Note:
 *  You have to import the prettyAlert function
 *  You have to place a div in the document called '_orongo_ajax_response'
 * 
 * Last edit: 09-01-2012 by Jaco Ruit
 */


function postComment(turl, articleID, comment, websitename){
    $.ajax({
       type: 'POST',
       url: turl,
       dataType: 'json',
       data:{
           content: comment,
           article: articleID
       },
       success: function(data, textStatus, jqXHR){
           prettyAlert('#_orongo_ajax_response', data.response, websitename );
       },
       error: function(jqXHR, textStatus, errorThrown){
           alert("An error occured while posting your comment. ");
       }
    });
};