( function( $ ) {
  "use strict";



  // DOCUMENT READY
  $( function() {
    if($('.osetin-intro-box.activate-theme-box').length){
      $(document).off('submit', 'form');
      $(document).off('click', 'input[type="submit"]');
      $('.activate-theme-box form').submit(function(){
        $('.activate-theme-box #publish').val('Loading');
        $(window).unbind('beforeunload');
      });
    }
  } );


} )( jQuery );
