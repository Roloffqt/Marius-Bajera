/**
 * Created by mads- on 31-01-2017.
 */
function remove(id, mode, text) {
     mode = mode || "?mode=delete&id=" + id;
     text = text || "Vil du slette denne record?";
     if (confirm(text)) {
          //alert(mode);
          document.location.href = mode;
     }
}
$(function () {
     $('.summernote').summernote({
          height: 300,
          lang: 'da-DK',
          toolbar: [
               ['mybutton', ['hello']],
               ['style', ['style']],
               ['font', ['bold', 'italic', 'underline', 'color', 'clear']],
               ['para', ['ol', 'ul', 'paragraph']],
               ['insert', ['link', 'picture', 'video']],
               ['table', ['table']],
               ['misc', ['fullscreen', 'codeview', 'help']]
          ]
     });
});