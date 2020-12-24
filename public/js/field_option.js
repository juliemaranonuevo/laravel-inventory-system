$(document).ready(function(){
    $('#reference_library').addClass('active');
    $('#fields').addClass('active'); 
});

$(function () {
    $('#table').DataTable();
})

$(document).on('change', '.type', function(){
    if($(this).val() == 'Option'){
        $('.option_div').removeClass('collapse');
    }
    else{
        $('.option_div').addClass('collapse');
    }
});