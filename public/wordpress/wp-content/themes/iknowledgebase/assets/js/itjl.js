
var ITJ = ITJ || {};

ITJ.core = function() {
  var self = {
    load: function() {
      jQuery(document).ready(self.ready);
    },
    ready: function() {
      self.initializeMenu();
      self.faqSetup();
    },
    initializeMenu: function() {
      jQuery("#opening_time_id").on('click', function(){
          var className = jQuery('#opening_time_id').attr('class');
          if(className === 'opening_time'){
               jQuery('#opening_time_id').addClass('active');
          }
          else{
               jQuery('#opening_time_id').removeClass('active');
          }
      });

    },
    faqSetup: function(){
      jQuery(".question").click(function(){
        jQuery(this).toggleClass('close-modle');
        jQuery(this).parent().find('.answer').toggleClass('is-hidden');
      });
    }

  };
  return self;
}();
ITJ.core.load();
