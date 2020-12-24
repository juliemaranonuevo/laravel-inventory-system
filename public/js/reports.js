$(document).ready(function(){
    $('#reports').addClass('active');
});

$('#reservation').daterangepicker(
{
    locale: {
        format : 'YYYY-MM-DD' 
    },
});

$(document).on('click', '#inventoryReports', function(){    

    window.open('', 'TheInventoryWindow');
    document.getElementById('TheInventoryForm').submit();

});

$(document).on('click', '#auditTrailReports', function(){    
    
    window.open('', 'TheAuditTrailWindow');
    document.getElementById('auditTrailForm').submit();

});

