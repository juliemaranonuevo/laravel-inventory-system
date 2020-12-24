$(document).ready(function(){
    $('#users').addClass('active');
});

if(document.getElementById("table-users")){
    reload_users();
}

$(document).on('change', '#filterByOffice', function(){    
    var office = $(this).val();
    reload_users(office);
});

$(document).on('click', '.refresh', function(){
    document.getElementById("filterByOffice").selectedIndex = 0;
    reload_users();
});

//Reload_users
function reload_users(office){
$("#table-users").dataTable().fnDestroy()
var t = $('#table-users').DataTable({
    responsive: true,
    autoWidth:false,
    paging: true,
    lengthChange: false,
    pageLength: 50,
    processing: true,
    serverSide: true,
    searchDelay: 1000,

    ajax: {
        url: '/users/search',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        data:{"office": office},
    },
    columns: [

        { title: '#' , data: null, sortable: false, 
            render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }  
        },
        { data: 'employee_number' },
        { data: 'name' },
        { data: 'email' },
        { data: 'office_code' },
        { data: 'created_dateTime' },
        { data: 'updated_dateTime' },
        {
            render: function ( data, type, row, meta ) {
                 return  '<a href="/users/' + row.id + '/edit" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>';
            }
        },
    ],
    columnDefs: [ 
        { targets: [ 0 ], orderable: false}],
    language: {
        emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
        zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'
    },
    order: [[ 6, 'desc']],
});

t.on( 'order.dt search.dt', function () {
    t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
} ).draw();
}
//End Reload_users