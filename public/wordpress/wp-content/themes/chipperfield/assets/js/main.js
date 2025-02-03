/* global jQuery */
var ITJ = ITJ || {};

ITJ.core = function() {
  var self = {
    load: function() {
      jQuery(document).ready(self.ready);
    },
    ready: function() {
      self.initializeSlider();
      jQuery("#chipper-search").on("click", self.initSearch);
      jQuery(document).on('click', '#btn-menu', self.toggleMenu);
    },

    initSearch: function() {
      jQuery('#search-form').toggleClass("active");
    },
    
    toggleMenu: function() {
      if (jQuery(this).hasClass('active')) {
        jQuery(this).removeClass('active');
        jQuery('#mm-menu').removeClass('active');
      }
      else {
        jQuery(this).addClass('active');
        jQuery('#mm-menu').addClass('active');
      }
    },

    initializeSlider: function() {

      jQuery('#all-categories h5+ul').slick({
        centerPadding: '30px',
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1200,
            settings: {
              slidesToShow: 3
            }
          },
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3
            }
          },
          {
            breakpoint: 990,
            settings: {
              slidesToShow: 2
            }
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1
            }
          }
        ]
      });
    }

  };
  return self;
}();
ITJ.core.load();

ITJ.homepage = function() {
  var self = {
    load: function() {
      jQuery(document).ready(self.ready);
    },
    ready: function() {
      if (jQuery("body.home").length > 0) {
        self.initializeSlider();
      }
    },

    initializeSlider: function() {

      jQuery('.custom-products-posts-slider .elementor-posts').slick({
        centerPadding: '30px',
        slidesToShow: 6,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1200,
            settings: {
              slidesToShow: 5
            }
          },
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 4
            }
          },
          {
            breakpoint: 990,
            settings: {
              slidesToShow: 3
            }
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 2
            }
          }
        ]
      });
    }

  };
  return self;
}();
ITJ.homepage.load();


ITJ.singlePost = function() {
  var self = {
    load: function() {
      jQuery(document).ready(self.ready);
    },
    ready: function() {
      if (jQuery("body.single").length > 0) {
        self.initializeSlider();
      }
    },

    initializeSlider: function() {

      jQuery('.custom-products-posts-slider .elementor-posts').slick({
        //infinite: true,
        centerPadding: '30px',
        slidesToShow: 6,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1200,
            settings: {
              slidesToShow: 5
            }
          },
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 4
            }
          },
          {
            breakpoint: 990,
            settings: {
              slidesToShow: 3
            }
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 2
            }
          }
        ]
      });
    }

  };
  return self;
}();
ITJ.singlePost.load();
