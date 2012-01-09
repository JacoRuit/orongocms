/*
 * Orongo Javascript
 * ************************************
 * 
 * jQuery function
 * 
 * Note:
 *  You have to place a div in the document called '_orongo_comments'
 * 
 * Last edit: 09-01-2012 by Jaco Ruit
 */


var noff;
var nlcid;

function loadComments(turl, articleID, lastCommentID, offset ){

    noff = offset;
    nlcid = lastCommentID;
    
    $.ajax({
       type: 'POST',
       url: turl,
       dataType: 'json',
       data:{  
           article: articleID,
           last_comment_id: lastCommentID,
           offset: offset
       },
       success: function(data, textStatus, jqXHR){
           if(data.response_code == '31'){
               $("#_orongo_comments").html(data.html + $("#_orongo_comments").html() );
               noff = noff + data.count;
               nlcid = data.newLastCommentID;
           }
       },
       error: function(jqXHR, textStatus, errorThrown){
           alert("An error occured while loading new comments. ");
       },
       async: false
    });
    
    var toReturn = new Array(2);
    toReturn[0] = noff;
    toReturn[1] = nlcid;
    return toReturn;
};