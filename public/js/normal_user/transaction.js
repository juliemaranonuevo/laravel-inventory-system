
$('#inventory').addClass('active');
$('#overview').addClass('active');
$('.select2').select2();

$(function () {

    $('#table').DataTable( {
        
        responsive: true,
        autoWidth:false,
        paging: true,
        lengthChange: false,
        pageLength: 50,
        columnDefs: 
        [ 
            { targets: [ 0, 1, 2, 4 ], orderable: false}
        ],
        ColumnDefs: [ {
            targets: [ ],
            orderable: false
        } ],
        language: {
            emptyTable: '<center><span class="label label-danger">NO INVENTORY RECORDS FOUND</span></center>',
            emptyTable: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'
        },
        // scrollX: true,
        order: [[ 3, 'desc']]

    });
    
})