$(document).ready(function(){
    $('#offices').addClass('active');
});

// $('#table-offices').DataTable();

if(document.getElementById("table-offices")){
    reload_offices();
}
//Reload_offices
function reload_offices(){
$("#table-offices").dataTable().fnDestroy()
var t = $('#table-offices').DataTable({
    responsive: true,
    autoWidth:false,
    paging: true,
    lengthChange: false,
    pageLength: 50,
    processing: true,
    serverSide: true,
    searchDelay: 1000,

    ajax: {
        url: '/offices/search',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
    },
    columns: [

        { data: null, sortable: false, 
            render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }  
        },
        { data: 'office_name' },
        { data: 'office_code' },
        { data: 'telephone' },
        {
            render: function ( data, type, row, meta ) {
                    return  '<a href="/offices/' + row.id + '/edit" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>';
            }
        },
    ],
    columnDefs: [ 
        { targets: [ 3 ], orderable: false}],
    language: {
        emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
        zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'
    },
    order: [[ 0, 'asc']],
});

t.on( 'order.dt search.dt', function () {
    t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
} ).draw();
}
//End Reload_offices