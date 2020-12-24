$(document).ready(function(){
    $('#inventory').addClass('active');
    $('#transaction_logs').addClass('active');
});

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

$(document).on('change', '#filter', function()
{
    var id = $(this).val();
    $.ajax({
        url:"/transaction-logs/search/"+id,
        dataType:"json",
        data:{ "hint" : hint},
        success:function(html)   
        {
            $('#resulthere').html(html.data);
        }
    })

    document.getElementById("result").options.length = 0;
    document.getElementById("result").append(new Option("All", "value"));

});

if(document.getElementById("result").value == ''){
    var office = null;
    reload(hint, office)
} 

$(document).on('change', '#filterByOffice', function(){
    document.getElementById("filter").selectedIndex = 0;
    document.getElementById("result").options.length = 0;
    document.getElementById("result").append(new Option("All", "value"));
    $('#result').attr("disabled", true);
    var office = $(this).val();
    reload(hint, office)
});

$(document).on('change', '#filter', function(){
    if ($(this).val() == '') {
        $('#result').attr("disabled", true);
    } else {
        $('#result').attr("disabled", false);
    }
    
    var office = $('#filterByOffice').val();
    reload(hint, office)
});

function reload(hint, office){
    var url = '/transaction-logs/search';
    if(document.getElementById("transaction")){
        $("#transaction").dataTable().fnDestroy()
        var t = $('#transaction').DataTable({
            
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Save in EXCEL',
                    filename: 'transaction_logs-'+ date +'-'+time,
                    exportOptions: {
                        orthogonal: 'export',
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        stripNewlines: true,
                        modifier: {
                            search: 'applied',
                            order: 'applied'
                        }
                    },
                    messageTop: 'Date Exported: '+ date +' | '+time,
                    title: 'Provincial Government of Laguna - Inventory System',
                    customize: function ( xlsx ){
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var rows = $('row c', sheet);
                        $('row c[r^="A2"]', sheet).attr('s', '52');
                        var CellColHeaders = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                        var CellColContent = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
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
                data:{ "hint" : hint, "office": office},
            },
            columns: [
                { data: null, sortable: false, 
                    render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                { data: 'office_code' },
                { data: 'item_name' },
                { data: 'stock_keeping_unit' },
                { data: 'name' },
                { data: 'output' },
                { data: 'transaction_date' },
                { data: 'remarks' },
                { data: 'updated_at' },
                { data: 'type' },
            ],
            columnDefs: [ 
                { targets: [ 0 ], orderable: false}],
            language: {
                emptyTable: '<center><span class="label label-danger">NO RECORDS FOUND</span></center>',
                zeroRecords: '<center><span class="label label-danger">NO MATCHING RECORDS FOUND</span></center>'
            },
            order: [[ 8, 'desc']],
        });

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    }
}


$(document).on('change', '#result', function(){
    var office = $('#filterByOffice').val();
    var id = $(this).val();
    var url = '/transaction-logs/search/'+id;
    if(document.getElementById("transaction")){
        $("#transaction").dataTable().fnDestroy()
        var t = $('#transaction').DataTable({
                dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'excel',
                    text: 'Save in EXCEL',
                    filename: 'transaction_logs-'+ date +'-'+time,
                    exportOptions: {
                        orthogonal: 'export',
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                    },
                    messageTop: 'Date Exported: '+ date +' | '+time,
                    // title: 'The information in this table is copyright to Provincial Government of Laguna.',
                    title: 'Provincial Government of Laguna - Inventory System',
                    customize: function ( xlsx ){
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var rows = $('row c', sheet);
                        $('row c[r^="A2"]', sheet).attr('s', '52');
                        var CellColHeaders = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                        var CellColContent = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

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
                data:{ "hint" : hint, "office": office},
            },
            columns: [
                
                { data: null, sortable: false, 
                    render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                { data: 'office_code' },
                { data: 'item_name' },
                { data: 'stock_keeping_unit' },
                { data: 'name' },
                { data: 'output' },
                { data: 'transaction_date' },
                { data: 'remarks' },
                { data: 'updated_at' },
                { data: 'type' },
    
            ],
            columnDefs: [ 
                { targets: [ 4 ], orderable: false}],
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
});
