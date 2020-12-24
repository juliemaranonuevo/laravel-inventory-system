
$('#inventory').addClass('active');
$('#overview').addClass('active');

$( function () {

    $('.select2').select2({

        tags: true

    });

})

$(document).on('change', '.select2', function() {

    var category = $('#category_id').val();
    var item = $(this).val();
    
    $.ajax({

       url:"/inventories/"+item+"/"+category,
       dataType:"json",
       success:function(html) {

            $('#Existing').html(html.data);

       }

    });
    
});

if ( document.getElementById("table") ) {

    $("#table").dataTable().fnDestroy()

    var t = $('#table').DataTable( {

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
            
        },

        columns: [
            
            { 
                data: null, 
                sortable: false, 
                render: function (data, type, row, meta) {

                    return meta.row + meta.settings._iDisplayStart + 1;

                }  
            },
            
            { data: 'stock_keeping_unit' },
            { data: 'name' },

            {
                render: function ( data, type, row, meta ) {

                    if (row.sticker == 1) {

                        if (row.in > 0) {

                            return  '<a href="javascript:void(0);" class="pull-right in_transaction" id="'+row.quantity_id+'">'+row.in+'</a>';

                        } else {

                            return '<span class="pull-right">0</span';

                        }

                    } else {

                        return  '<a href="javascript:void(0);" class="pull-right in_transaction_withoutSticker" id="'+row.quantity_id+'">'+row.in+'</a>';

                    }
                    
                }
            },

            {
                render: function ( data, type, row, meta ) {

                    if (row.sticker == 1) {

                        if (row.out > 0) {

                            return  '<a href="javascript:void(0);" class="pull-right out_transaction" id="'+row.quantity_id+'">'+row.out+'</a>';

                        } else {

                            return '<span class="pull-right">0</span';

                        }

                    } else {

                        if (row.out > 0) {

                            return  '<a href="javascript:void(0);" class="pull-right out_transaction_withoutSticker" id="'+row.quantity_id+'">'+row.out+'</a>';

                        } else {

                            return '<span class="pull-right">0</span';

                        }

                    }

                }
            },

            {
                render: function ( data, type, row, meta ) {
                    if (row.sticker == 1) {

                        if (row.condemned > 0) {

                            return  '<a href="javascript:void(0);" class="pull-right condemned_transaction" id="'+row.quantity_id+'">'+row.condemned+'</a>';

                        } else {

                            return '<span class="pull-right">0</span';

                        }

                    } else {

                        return '<span class="pull-right">N/A</span';

                    }

                }
            },

            { 
                render: function ( data, type, row, meta ) {

                    return '<span class="pull-right">'+row.balance+'</span';

                }
            },

            {
                render: function ( data, type, row, meta ) {

                    var buttons;

                    if (row.sticker == 1) {

                        buttons = '<a href="/inventories/' + row.quantity_id + '" class="btn btn-sm btn-info" style="margin-right: 5px;"><i class="fa fa-plus-square"></i> Add Transaction</a>';
                        buttons += '<a href="/inventories/' + row.id + '/edit" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa fa-question-circle"></i> Update</a>';
                        buttons += '<a href="/inventories/' + row.id + '/stickers/'+ row.quantity_id +'" class="btn btn-sm btn-info" style="margin-right: 5px;"><i class="fa fa-eye"></i> View Stickers</a>';
                    
                    } else {

                        buttons = '<a href="/inventories/' + row.quantity_id + '" class="btn btn-sm btn-info" style="margin-right: 5px;"><i class="fa fa-plus-square"></i> Add Transaction</a>';
                        buttons += '<a href="/inventories/' + row.id + '/edit" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa fa-question-circle"></i> Update</a>';
                    
                    }

                    return  buttons;

                }
            }
        ],

        columnDefs: [{ targets: [ 1, 2, 3, 4, 5, 6, 7 ], orderable: false}],

        language: {

            emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
            zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

        },

        order: [[ 0, 'asc']],
            
    });

    t.on( 'order.dt search.dt', function () {

        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

            cell.innerHTML = i+1;

        });

    }).draw();

}

$(document).on('click', '#close', function() {

    $("#table-modal").dataTable().fnDestroy()
    $('#table-modal tbody').empty();

});

//with sticker
$(document).on('click', '.in_transaction', function() {

    $('.modal-title').html('In Transaction');
    var id = $(this).attr('id');
    var status = 'IN';

    withSticker(id, status);

});

$(document).on('click', '.out_transaction', function() {

    $('.modal-title').html('Out Transaction');
    var id = $(this).attr('id');
    var status = 'OUT';

    withSticker(id, status);

});

$(document).on('click', '.condemned_transaction', function() {

    $('.modal-title').html('Condemned Transaction');
    var id = $(this).attr('id');
    var status = 'CONDEMNED';

    withSticker(id, status);

});

function withSticker(id, status) {
    var t = $('#table-modal').DataTable( {
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
            { 
                title: '#' , 
                data: null, 
                sortable: false, 
                render: function (data, type, row, meta) {

                    return meta.row + meta.settings._iDisplayStart + 1;

                }  
            },

            { title: 'Property Number', data: 'property_number' },
            { title: 'Last Transaction Date', data: 'created_at' },
            { title: 'Date Encoded', data: 'updated_at' },
            { 
                title: 'Type',
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

        columnDefs: [{ targets: [ 0, 1, 2, 4 ], orderable: false}],

        language: {

            emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
            zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

        },

        order: [[ 3, 'desc']],

    });

    t.on( 'order.dt search.dt', function () {

        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

            cell.innerHTML = i+1;

        });

    }).draw();

    $('#modal-default').modal('show');

}

//without sticker
$(document).on('click', '.in_transaction_withoutSticker', function() {

    $('.modal-title').html('In Transaction');
    var id = $(this).attr('id');
    var status = 'IN';

    withOutSticker(id, status);

});

$(document).on('click', '.out_transaction_withoutSticker', function() {

    $('.modal-title').html('In Transaction');
    var id = $(this).attr('id');
    var status = 'OUT';

    withOutSticker(id, status);

});

function withOutSticker(id, status) {

    var t = $('#table-modal').DataTable( {

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

            { 
                title: '#' , 
                data: null, 
                sortable: false, 
                render: function (data, type, row, meta) {

                    return meta.row + meta.settings._iDisplayStart + 1;

                }  
            },

            { title: 'Quantity', data: 'quantity' },
            { title: 'Last Transaction Date', data: 'transaction_date' },
            { title: 'Date Encoded', data: 'created_at' },
            { 
                title: 'Type',

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

        columnDefs: [{ targets: [ 0, 1, 2, 4 ], orderable: false}],

        language: {

            emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
            zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

        },

        order: [[ 3, 'desc']],

    });

    t.on( 'order.dt search.dt', function () {

        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

            cell.innerHTML = i+1;

        });

    }).draw();

    $('#modal-default').modal('show');
}