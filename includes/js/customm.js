
jQuery( document ).ready(function() {
if ( screen.width > 768 ){
        jQuery('.spin-overlay').mousedown(function(){    
    var $this = jQuery(this);        
jQuery($this).mousemove(function(){
    jQuery($this).hide();
});       
});
}
else{
jQuery('.spin-overlay').bind('touchstart', function(e) {
    jQuery('.spin-overlay').bind('touchmove', function(e) {
              var $this = jQuery(this);
              //alert($this);
                             jQuery($this).children(".drag , .spin-drag").hide();
                             jQuery($this).css("background", "rgba(0, 0, 0, 0)");
                       
                    });
                });
                }
});
