(function($) {
  $('.monthly-offer-banner-img').on('click', function() {
      $('.newsletter').addClass("active");
      $('html, body').animate({scrollTop:$(".newsletter").offset().top}, 'slow');
      $('input#newsletter').focus();
  });
  $('.overlay').on('click', function() {
    $('.newsletter').removeClass("active");
})
})( jQuery );