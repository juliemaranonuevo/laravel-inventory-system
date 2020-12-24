$(document).ready(function(){
    $('#inventory').addClass('active');
    $('#overview').addClass('active');
    $('.select2').select2({
        tags: true
    });
});


if(document.getElementById("table")){
    reload();
}

$(document).on('change', '#filterByOffice', function(){    
      var office = $(this).val();
      reload(office);
});

$(document).on('click', '.refresh', function(){
    document.getElementById("filterByOffice").selectedIndex = 0;
    reload();
});

//Reload
function reload(office){
    $("#table").dataTable().fnDestroy()
    $('#table tbody').empty();
    var t = $('#table').DataTable({
        responsive: true,
        autoWidth:false,
        paging: true,
        lengthChange: false,
        pageLength: 50,
        processing: true,
        serverSide: true,
        searchDelay: 1000,
        ajax: {
            url: '/inventories/search/',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{"office": office},
        },
        columns: [
            
            { data: null, sortable: false, 
                render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },
            { data: 'office_code' },
            { data: 'stock_keeping_unit' },
            { data: 'name' },
            {
                render: function ( data, type, row, meta ) {
                    if (row.sticker == 1) {
                        if (row.in > 0) {
                            return  '<a href="javascript:void(0);" class="in_transaction" id="'+row.quantity_id+'">'+row.in+'</a>';
                        } else {
                            return 0;
                        }
                    } else {
                        return  '<a href="javascript:void(0);" class="in_transaction_withoutSticker" id="'+row.quantity_id+'">'+row.in+'</a>';
                    }
                    
                }
            },
            {
                render: function ( data, type, row, meta ) {
                    if (row.sticker == 1) {
                        if (row.out > 0) {
                            return  '<a href="javascript:void(0);" class="out_transaction" id="'+row.quantity_id+'">'+row.out+'</a>';
                        } else {
                            return 0;
                        }
                    } else {
                        return  '<a href="javascript:void(0);" class="out_transaction_withoutSticker" id="'+row.quantity_id+'">'+row.out+'</a>';
                    }
                }
            },
            {
                render: function ( data, type, row, meta ) {
                    if (row.sticker == 1) {
                        if (row.condemned > 0) {
                            return  '<a href="javascript:void(0);" class="condemned_transaction" id="'+row.quantity_id+'">'+row.condemned+'</a>';
                        } else {
                            return 0;
                        }
                    } else {
                        return 'N/A';

                    }
                }
            },
            { data: 'balance' },
            {
                render: function ( data, type, row, meta ) {
                    if (row.sticker == 1) {
                        return  '<a href="/inventories/' + row.id + '/stickers/' + row.quantity_id + '" class="btn btn-primary btn-block"><i class="fa fa-eye"></i> View Stickers</a>';
                    } else {
                        return null;
                    }
                }
            }
        ],
    columnDefs: [ 
        { targets: [ 0 ], orderable: false}],
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
//End Reload

//modal------->
$(document).on('click', '#close', function(){
    $("#table-modal").dataTable().fnDestroy()
    $('#table-modal tbody').empty();
});

//with sticker
$(document).on('click', '.in_transaction', function(){
    $('.modal-title').html('In Transaction');
    var id      = $(this).attr('id');
    var status  = 'IN';
    withSticker(id, status);
});

$(document).on('click', '.out_transaction', function(){
    $('.modal-title').html('Out Transaction');
    var id = $(this).attr('id');
    var status  = 'OUT';
    withSticker(id, status);
});

$(document).on('click', '.condemned_transaction', function(){
    $('.modal-title').html('Condemned Transaction');
    var id = $(this).attr('id');
    var status  = 'CONDEMNED';
    withSticker(id, status);
});

function withSticker(id, status){
    var t = $('#table-modal').DataTable({
        responsive: true,
        autoWidth:false,
        paging: true,
        lengthChange: false,
        pageLength: 50,
        processing: true,
        serverSide: true,
        searchDelay: 1000,
    
        ajax: {
            url: '/transactions/'+id+'/search',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{"status": status},
        },
        columns: [
    
            { title: '#' , data: null, sortable: false, 
                render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },
            { title: 'Office', data: 'office_code' },
            { title: 'Property Number', data: 'property_number' },
            { title: 'Last Transaction Date', data: 'created_dateTime' },
            { title: 'Date Encoded', data: 'updated_dateTime' },
            { title: 'Type',
                render: function ( data, type, row, meta ) {
                    if (row.type == 'IN') {
                        return  '<mark style="background-color: green; color: white;">' + row.type + '</mark>';
                    } else if (row.type == 'OUT') {
                        return  '<mark style="background-color: orange; color: white;">' + row.type + '</mark>';
                    } else {
                        return  '<mark style="background-color: red; color: white;">' + row.type + '</mark>';
                    }
                }
            }
        ],
        columnDefs: [ 
            { targets: [ 0 ], orderable: false}],
        language: {
            emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
            zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'
        },
        order: [[ 4, 'desc']],
    });
    
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    
    $('#modal-default').modal('show');
}
//End with sticker

//--------------->

//without sticker
$(document).on('click', '.in_transaction_withoutSticker', function(){
    $('.modal-title').html('In Transaction');
    var id      = $(this).attr('id');
    var status  = 'IN';
    withOutSticker(id, status);
});

$(document).on('click', '.out_transaction_withoutSticker', function(){
    $('.modal-title').html('In Transaction');
    var id      = $(this).attr('id');
    var status  = 'OUT';
    withOutSticker(id, status);
});

function withOutSticker(id, status){
    var t = $('#table-modal').DataTable({
        responsive: true,
        autoWidth:false,
        paging: true,
        lengthChange: false,
        pageLength: 50,
        processing: true,
        serverSide: true,
        searchDelay: 1000,
    
        ajax: {
            url: '/transactions/'+id+'/search',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{"status": status},
        },
        columns: [
    
            { title: '#' , data: null, sortable: false, 
                render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
                }  
            },
            { title: 'Office', data: 'office_code' },
            { title: 'Quantity', data: 'quantity' },
            { title: 'Last Transaction Date', data: 'transaction_date' },
            { title: 'Date Encoded', data: 'created_dateTime' },
            { title: 'Type',
                render: function ( data, type, row, meta ) {
                    if (row.type == 'IN') {
                        return  '<mark style="background-color: green; color: white;">' + row.type + '</mark>';
                    } else if (row.type == 'OUT') {
                        return  '<mark style="background-color: orange; color: white;">' + row.type + '</mark>';
                    } else {
                        return  '<mark style="background-color: red; color: white;">' + row.type + '</mark>';
                    }
                }
            }
        ],
        columnDefs: [ 
            { targets: [ 0 ], orderable: false}],
        language: {
            emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
            zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'
        },
        order: [[ 4, 'desc']],
    });
    
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    
    $('#modal-default').modal('show');
}
//End without sticker
