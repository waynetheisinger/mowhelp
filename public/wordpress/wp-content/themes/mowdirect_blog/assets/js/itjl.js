
var ITJ = ITJ || {};

ITJ.core = function() {
  var self = {
    load: function() {
      jQuery(document).ready(self.ready);
    },
    ready: function() {
      self.initializeMenu();
      if(jQuery(".category-posts-slider").length){
        self.initializeSlider(".category-posts-slider");
      }
      self.loadHomecategoryAjax();
      self.categorySearchForm();
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
    
    initializeSlider: function(selector){
      jQuery(selector).slick({
        infinite: false,
        slidesToShow: 4,
        slidesToScroll: 3,
        responsive: [
          {
            breakpoint: 1080,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
            }
          },
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
      
      jQuery(selector).removeClass("flag-slick");
    },
    loadHomecategoryAjax : function(){
      
      if(jQuery(".homeCategory").length < 1){
        return true;
      }
      
      jQuery(".homeCategorySeeMore").click(function(){
        jQuery(".homeCategorySeeMore").unbind("click");
        jQuery(".homeCategorySeeMore").remove();
        
        var offset = jQuery(this).data('offset');
        var ajaxLoaded = jQuery(this).data('ajax');
        if(ajaxLoaded == '0'){
          jQuery(this).data('ajax', "1");
           jQuery.ajax({
            type: "GET",
            dataType: "json",
            url:
              "https://mowblog.dev.superbotics.in/wp-json/v1/itjl/home_category?offset=" + offset
          })
          .then(function(data) {
              jQuery(".homeCategory").last().after(data.render_html);
              ".category-posts-slider"
              setInterval(function () {
                self.initializeSlider('.flag-slick');
                self.loadHomecategoryAjax();
              }, 500);
          });
        }
       
        
      });
      
      
    },
    categorySearchForm: function (){
      if(jQuery("#category-search form").length){
        jQuery("#category-search form").removeAttr("action");
      }
      
    }

  };
  return self;
}();
ITJ.core.load();




