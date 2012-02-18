/*
 * Orongo Javascript
 * ************************************
 * 
 * jQuery function
 * 
 * 
 * Last edit: 18-02-2012 by Jaco Ruit
 */

var notifier;
function fetchNotifications(turl){
 
   $.ajax({
       type: 'POST',
       url: turl,
       dataType: 'json',
       success: function(data, textStatus, jqXHR){
           notifier = data;
       },
       error: function(jqXHR, textStatus, errorThrown){
       }
    });
    if(notifier.newNotifications == true){
        $.each(notifier.notifications, function(key, value) { 
            if(value.image != null){
                $.gritter.add({
                    title: value.title,
                    text: value.text,
                    time: value.time,
                    image: value.image
                });  
            }else{
                $.gritter.add({
                    title: value.title,
                    text: value.text,
                    time: value.time
                });
            }
        });
    }
    
};