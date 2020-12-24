
$('#inventory').addClass('active');
$('#transaction_logs').addClass('active');

$(document).on('change', '#filter', function() {
    
    var id = $(this).val();
    
    if ($(this).val() == '') {

        $('#result').attr("disabled", true);
        document.getElementById("result").options.length = 0;
        document.getElementById("result").append(new Option("All", "value"));

    } else {

        $('#result').attr("disabled", false);
        $.ajax({

            url:"/transaction-logs/search/"+id,
            dataType:"json",
            data:{ "hint" : hint},
            success:function(html)   
            {

                $('#resulthere').html(html.data);

            }

        })

    }

});

if (document.getElementById("result").value == '') {

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!

    var yyyy = today.getFullYear();

    if (dd < 10) {

        dd = '0' + dd;

    } 
    
    if (mm < 10) {

        mm = '0' + mm;

    } 

    var date = mm + '-' + dd + '-' +  yyyy;
    var time = today.toLocaleTimeString();

    var url = '/transaction-logs/search';

    if (document.getElementById("transaction")) {

        $("#transaction").dataTable().fnDestroy()

        var t = $('#transaction').DataTable( {

            responsive: true,
            autoWidth:false,
            paging: true,
            lengthChange: false,
            pageLength: 50,
            processing: true,
            serverSide: true,
            searchDelay: 1000,
            dom: 'Bfrtip',

            buttons: [
                {
                    extend: 'excel',
                    text: 'Save in EXCEL',
                    filename: 'transaction_logs-'+ date +'-'+time,
                    exportOptions: {
                        orthogonal: 'export',
                        columns: [0, 1, 2, 3, 4, 5, 6, 7],
                        stripNewlines: true,
                        modifier: {
                            search: 'applied',
                            order: 'applied'
                        }
                    },
                    messageTop: 'Date Exported: '+ date +' | '+time,
                    title: 'Provincial Government of Laguna - Inventory System',
                    customize: function ( xlsx ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var rows = $('row c', sheet);
                        
                        $('row c[r^="A2"]', sheet).attr('s', '52');
                        var CellColHeaders = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                        var CellColContent = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',];
                        for ( y=0; y < CellColHeaders.length; y++ ) {

                                $('row:eq(2) c[r^='+CellColHeaders[y]+']', sheet).attr( 's', '27' );//bold with border

                        }
                        
                        for ( i=3; i < rows.length; i++ ) {

                            for ( y=0; y < CellColContent.length; y++ ) {

                                    $('row:eq('+i+') c[r^='+CellColContent[y]+']', sheet).attr( 's', '25' );//with border

                            }
                            
                        }
                    },
                },
            ],
           
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

                { data: 'item_name' },
                { data: 'stock_keeping_unit' },
                { data: 'name' },
                { data: 'output' },
                { data: 'transaction_date' },
                { data: 'remarks' },
                { data: 'updated_at' },
                { data: 'type' },
            ],

            columnDefs: [{ targets: [ 0, 1, 2, 3, 4, 5, 6, 8 ], orderable: false}],

            language: {

                emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
                zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

            },

            order: [[ 7, 'desc']],

        });

        t.on( 'order.dt search.dt', function () {

            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

                    cell.innerHTML = i+1;

            });

        }).draw();

    }

} 

$(document).on('change', '#filter', function() {

    var url = '/transaction-logs/search';

    if (document.getElementById("transaction")) {

        $("#transaction").dataTable().fnDestroy()

        var t = $('#transaction').DataTable( {
            responsive: true,
            autoWidth:false,
            paging: true,
            lengthChange: false,
            pageLength: 50,
            processing: true,
            serverSide: true,
            searchDelay: 1000,
            dom: 'Bfrtip',

            buttons: [
                {
                    extend: 'excel',
                    text: 'Save in EXCEL',
                    filename: 'transaction_logs-'+ date +'-'+time,
                    exportOptions: {
                        orthogonal: 'export',
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    messageTop: 'Date Exported: '+ date +' | '+time,
                    title: 'Provincial Government of Laguna - Inventory System',
                    customize: function ( xlsx ){
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var rows = $('row c', sheet);
                        $('row c[r^="A2"]', sheet).attr('s', '52');
                        var CellColHeaders = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                        var CellColContent = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',];

                        for ( y=0; y < CellColHeaders.length; y++ ) {
                            $('row:eq(2) c[r^='+CellColHeaders[y]+']', sheet).attr( 's', '27' );//bold with border
                        }

                        for ( i=3; i < rows.length; i++ ) {
                            for ( y=0; y < CellColContent.length; y++ ) {
                                $('row:eq('+i+') c[r^='+CellColContent[y]+']', sheet).attr( 's', '25' );//with border
                            }
                        }
                    }
                },
            ],
           
            ajax: {

                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ "hint" : hint },

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
                { data: 'output' },
                { data: 'transaction_date' },
                { data: 'remarks' },
                { data: 'updated_at' },
                { data: 'type' },
    
            ],

            columnDefs: [{ targets: [ 0, 1, 2, 3, 4, 5, 6, 8 ], orderable: false}],

            language: {
                    
                emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
                zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

            },

            order: [[ 7, 'desc']],
            
        });

        t.on( 'order.dt search.dt', function () {

            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

                    cell.innerHTML = i+1;
            
            });

        }).draw();

    }

})

$(document).on('change', '#result', function() {

    var id = $(this).val();

    var url = '/transaction-logs/search/'+id;

    if(document.getElementById("transaction")) {

        $("#transaction").dataTable().fnDestroy()

        var t = $('#transaction').DataTable({
            responsive: true,
            autoWidth:false,
            paging: true,
            lengthChange: false,
            pageLength: 50,
            processing: true,
            serverSide: true,
            searchDelay: 1000,
            dom: 'Bfrtip',
            
            buttons: [
                {
                    extend: 'excel',
                    text: 'Save in EXCEL',
                    filename: 'transaction_logs-'+ date +'-'+time,
                    exportOptions: {
                        orthogonal: 'export',
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    messageTop: 'Date Exported: '+ date +' | '+time,
                    title: 'Provincial Government of Laguna - Inventory System',
                    customize: function ( xlsx ){
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var rows = $('row c', sheet);
                        $('row c[r^="A2"]', sheet).attr('s', '52');
                        var CellColHeaders = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                        var CellColContent = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',];

                        for ( y=0; y < CellColHeaders.length; y++ ) {
                            $('row:eq(2) c[r^='+CellColHeaders[y]+']', sheet).attr( 's', '27' );//bold with border
                        }

                        for ( i=3; i < rows.length; i++ ) {
                            for ( y=0; y < CellColContent.length; y++ ) {
                                $('row:eq('+i+') c[r^='+CellColContent[y]+']', sheet).attr( 's', '25' );//with border
                            }
                        }
                    }
                },
            ],
            
            ajax: {

                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ "hint" : hint },

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
                { data: 'output' },
                { data: 'transaction_date' },
                { data: 'remarks' },
                { data: 'updated_at' },
                { data: 'type' },
    
            ],

            columnDefs: [{ targets: [ 0, 1, 2, 3, 4, 5, 6, 8 ], orderable: false}],

            language: {

                emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
                zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'

            },

            order: [[ 7, 'desc']],
            
        });

        t.on( 'order.dt search.dt', function () {

            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

                cell.innerHTML = i+1;

            });

        }).draw();
 
    }

});