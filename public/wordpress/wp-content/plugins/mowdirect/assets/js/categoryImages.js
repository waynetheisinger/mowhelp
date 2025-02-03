(function($){
  $(function(){

    var picker, addImageButton, removeImageButton;
    $("form#addtag input[type='submit']").on("click", function(e){
      removeImage();
    })
    addImageButton = $("button.upload_image_button");
    addImageButton.on("click", openMediaPicker)
    removeImageButton = $("button.remove_image_button");
    removeImageButton.on("click", function(){removeImage(); return false });

    function openMediaPicker(e){
      picker = wp.media();
      console.log(picker)
      picker.open();
      picker.on("select", selectImage);
      return false;
    }

    function selectImage(e){
      var attachment = picker.state().get('selection').first().toJSON();
      console.log(attachment)
      var id = attachment.id;
      var url = attachment.url;
      var thumb = attachment.sizes.thumbnail.url
      $("input#category_image").val(id);
      document.getElementById("category_image_preview").src = thumb;
    }

    function removeImage(){
      $("input#category_image").val("");
      document.getElementById("category_image_preview").src = mowdirect.pluginUrl + "/assets/img/categoryPlaceholder.png";
    }

  })
})(jQuery);