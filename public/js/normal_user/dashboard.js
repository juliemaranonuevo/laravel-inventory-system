
$('#dashboard').addClass('active');

if ( document.getElementById("transaction") ) {

    var hint = 2;

    if ( hint != 2 ) {

        console.log("internal error!");

    } else {
      
        var url = '/transaction-logs/search';

        var t = $('#transaction').DataTable( {

            responsive: true,
            autoWidth:false,
            paging: true,
            lengthChange: false,
            pageLength: 50,
            processing: true,
            serverSide: true,
            searchDelay: 1000,

            ajax: {

                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ "hint" : hint},

            },

            columns: [
                
                { 
                    data: null, 
                    sortable: false,
                    render: function (data, type, row, meta) {

                        return meta.row + meta.settings._iDisplayStart + 1;

                    }
                },

                {
                    render: function ( data, type, row, meta ) {

                        if (row.sticker == 1) {

                            return  '<a href="/inventories/'+row.item_id+'/stickers/'+row.quantity_id+'">' + row.item_name + '</a>';

                        } else {

                            return  row.item_name ;

                        }
                       
                    }
                },

                { data: 'stock_keeping_unit' },
                { data: 'name' },
                { data: 'property_number' },
                { data: 'quantity' },
                { data: 'transaction_date' },
                { data: 'remarks' },
                { data: 'updated_at' },
                { data: 'type' },

            ],

            columnDefs: [ { targets: [ 0, 1, 2, 3, 4, 5, 6, 7, 9 ], orderable: false} ],

            language: {

                emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
                zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

            },

            order: [[ 8, 'desc']],
            
        });

        t.on( 'order.dt search.dt', function () {

            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

                cell.innerHTML = i+1;

            });

        }).draw();

    }

}

if ( document.getElementById("sticker") ) {

    if ( type == 0 || type == 1 || type == 2 ) {

        var url = '/info/search';

        $("#sticker").dataTable().fnDestroy()

        var t = $('#sticker').DataTable( {

            responsive: true,
            autoWidth:false,
            paging: true,
            lengthChange: false,
            pageLength: 50,
            processing: true,
            serverSide: true,
            searchDelay: 1000,

            ajax: {

                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ "type" : type},

            },
            columns: [

                { 
                    data: null, 
                    sortable: false, 
                    render: function (data, type, row, meta) {

                        return meta.row + meta.settings._iDisplayStart + 1;

                    }  
                },

                { data: 'item_name' },
                { data: 'stock_keeping_unit' },
                { data: 'property_number' },
                { data: 'name' },

                {
                    render: function ( data, type, row, meta ) {

                        return  '<p align="justify" style="word-break: break-all; white-space: normal;">' + row.article + '</p>';
                        
                    }
                },

                { data: 'brand_sn' },
                { data: 'remarks' },
                { data: 'date_count' },
                { data: 'memo_receipt_employee' },
                { data: 'updated_at' },

            ],

            columnDefs: [ {targets: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ], orderable: false}],
                
            language: {

                emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
                zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

            },

            order: [[ 10, 'desc']],

        } );

        t.on( 'order.dt search.dt', function () {

            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

                cell.innerHTML = i+1;

            });

        }).draw();

    } else if ( type == 3 ) {

        var url = '/info/search';

        $("#sticker").dataTable().fnDestroy()

        var t = $('#sticker').DataTable( {

            responsive: true,
            autoWidth:false,
            paging: true,
            lengthChange: false,
            pageLength: 50,
            processing: true,
            serverSide: true,
            searchDelay: 1000,

            ajax: {

                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ "type" : type}

            },

            columns: [
                
                { 
                    data: null, 
                    sortable: false,
                    render: function (data, type, row, meta) {

                        return meta.row + meta.settings._iDisplayStart + 1;

                    }  
                },

                { data: 'item_name' },
                { data: 'stock_keeping_unit' },
                { data: 'name' },
                { data: 'unit' },
                { data: 'balance' },
                { data: 'updated_at' },

            ],

            columnDefs: [{ targets: [ 0, 1, 2, 3, 4, 5 ], orderable: false, visible: true}],

            language: {

                emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
                zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

            },

            order: [[ 6, 'desc']],
            
        });

        t.on( 'order.dt search.dt', function () {

            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

                cell.innerHTML = i+1;

            });

        }).draw();

    }else {

        console.log("internal errorsss");

    }
    
}

