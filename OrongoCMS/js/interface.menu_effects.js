/*
 * Orongo Javascript
 * ************************************
 * 
 * jQuery document.ready function for:
 * 
 * documents using menu class
 * 
 * 
 * Last edit: 14-12-2011 by Jaco Ruit
 */

$(document).ready(function(){
    
    $(".menu").slideDown(500, function(){
        $(".seperator").show();
        $(".icon_messages_small").show();
        $(".icon_settings_small").show();
        $(".icon_pages_small").show();
        $(".menu_text.left.hide").fadeIn(1000);
        $(".menu_text").show();
                
        var shown = true;
        $(".menu").dblclick(function() {
            if(shown){
                $(".menu").animate({
                    opacity: 0.25,
                    height: '-=30',
                });
                shown = false;
            }else{
                $(".menu").animate({
                    opacity: 100,
                    height: '+=30',
                });
               shown = true;
            }
        });
    });
});