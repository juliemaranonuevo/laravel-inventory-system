$(document).ready(function() {
    var max_fields      = 10;
    var wrapper         = $(".container1"); 
    var add_button      = $(".add_form_field"); 
    
    $(add_button).click(function(e){ 
      e.preventDefault();
        $(wrapper).append('<div style="margin-top: 5px;"><table style="width: 100%"><tr><td style="width: 90%;"><input type="text" class="form-control" name="option[]" placeholder="Optional"></td><td style="width: 10%;" class="text-center"><a href="#" class="delete"><i class="fa fa-minus text-danger remove_form_field"></i></a></td></tr></table></div>');
            //add input box
    });
    
    $(wrapper).on("click",".remove_form_field", function(e){ 
        e.preventDefault();
        $(this).closest('div').remove();;
    })
});


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