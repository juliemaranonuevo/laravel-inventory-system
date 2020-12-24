$(document).ready(function(){
    $('#reference_library').addClass('active');
    $('#categories').addClass('active');
  });

  $(function () {
    $('#tbl_category').DataTable()
  })

  $(document).ready(function() {
  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass   : 'iradio_minimal-blue'
  })
});
