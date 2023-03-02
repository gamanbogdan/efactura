
   // Add the following code if you want the name of the file appear on select
   $(".custom-file-input").on("change", function() {

      var table = $('#efactura-datatable').DataTable();
      table.state.clear();


      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });







