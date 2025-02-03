(function($) {

  var categoryButton = $(".category-grid-toggle a.btn");
  var categoryGrid = $(".category-grid");
  var gridOpen = $("body.home").length > 0 ? true : false;

  function toggleCategoryGrid(e){
    if(gridOpen){
      console.log("Grid is open, closing");
      categoryGrid.slideUp(300, function(){ 
        gridOpen = false;
        categoryButton.text("SHOW CATEGORIES");
      });
    } else {
      console.log("Grid is closed, opening");
      categoryGrid.slideDown(300, function(){
        gridOpen = true;
        categoryButton.text("HIDE CATEGORIES");
       });
    }
  }

  categoryButton.on("click", toggleCategoryGrid);


})(jQuery);
