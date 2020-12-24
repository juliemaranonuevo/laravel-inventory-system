
$('#inventory').addClass('active');
$('#overview').addClass('active');

if ( document.getElementById("table-sticker") ) {

    var url = window.location.href;
    var arr = url.split("/");
    // var result_url =  arr[6] + "/" + "search" ;
    var result_url =  "/" + arr[3] + "/" + arr[4] + "/" + arr[5] + "/" + arr[6] + "/" + "search" ;
   
    // alert(result_url)
    // reload_stickers(result_url);
    reload_stickers(result_url);

}

function reload_stickers(result_url) {

    $("#table-sticker").dataTable().fnDestroy()

    var t = $('#table-sticker').DataTable( {

        responsive: true,
        autoWidth:false,
        paging: true,
        lengthChange: false,
        pageLength: 50,
        processing: true,
        serverSide: true,
        searchDelay: 1000,
    
        ajax: {

            url: result_url,
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

            { data: 'property_number' },
            { data: 'article' },
            { data: 'brand_sn' },
            { data: 'remarks' },
            { data: 'date_count' },
            { data: 'memo_receipt_employee' },

            {
                render: function ( data, type, row, meta ) {

                    if (row.type == 'IN') {

                        return  '<mark style="background-color: green; color: white;">' + row.type + '</mark>';

                    } else if (row.type == 'OUT') {

                        return  '<mark style="background-color: orange; color: white;">' + row.type + '</mark>';

                    } else {

                        return  '<mark style="background-color: red; color: white;">' + row.type + '</mark>';

                    }

                }
            },

            { data: 'updated_at' },

            {
                render: function ( data, type, row, meta ) {

                    return  '<a href="/sticker/' + row.id + '/edit" class="btn btn-sm btn-warning" style="margin-right: 5px;"><i class="fa fa-edit"></i> Edit</a>';
               
                }
            }
        ],

        columnDefs: [{ targets: [ 0, 1, 2, 3, 4, 5, 6, 7, ], orderable: false}],

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