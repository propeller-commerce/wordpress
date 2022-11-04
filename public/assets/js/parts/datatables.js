'use-strict';

(function ($) {
    var TableOrder = {
        init: function () {
            var oTable = $('.table-sorter').dataTable( {
                "paging":   true,
                "pagingType": "simple",
                "pageLength": 10,
                "ordering": true,
                "order" : [[0, "desc"]],
                "lengthChange": false,
                "destroy": true,
                "searching": false,
                "info": true,
                "responsive": true,
                "language": {
                    "info": "<span class='from-to'>_START_ - _END_</span> van _TOTAL_ orders",
                },
                "dom": '<"top"i<"clear">>rt<"bottom"ip<"clear">>',
                "columnDefs": [
                    { "orderDataType": "dom-text", "type": "numeric" },
                    { "orderDataType": "dom-text", "type": "numeric" },
                    { "orderDataType": "dom-text", "type": "numeric" },
                    { "orderDataType": "dom-text", "type": "numeric" },
                    { "orderDataType": "dom-text", "type": "numeric" },
                    { orderable: false, targets: 5}
                ],
                "drawCallback": function( settings ) {
                    $('.paginate_button.previous').html("<span class='icon prev-icon'></span>")
                    $('.paginate_button.next').html("<span class='icon next-icon'></span>")
                    var api = this.api();
                    var total_records = api.rows().count();
                    var page_length = api.page.info().length;
                    var total_pages = Math.ceil(total_records / page_length);
                    var current_page = api.page.info().page+1;
                    $('.paginate_button.previous').after("<span class='table-pages'> pagina " + current_page + " van " + (page_length < 0 ? 1 : total_pages) + "</span>");
                    if ( total_records < 10 ) {
                        $('.dataTables_paginate').hide();
                    }
                }
                
            } );  
            $('#sortTable').change( function () {

                switch ($(this).val()) {
                    case "order_no": 
                    oTable.fnSort( [ [0,'desc'] ] );
                    break;
                    
                    case "order_date":
                    oTable.fnSort( [ [1,'desc'] ] );
                    break;
                    
                    case "order_quantity":
                    oTable.fnSort( [ [2,'desc'] ] );
                    break;
                    
                    case "order_total":
                    oTable.fnSort( [ [3,'desc'] ] );
                    break;
                    
                    case "order_status":
                    oTable.fnSort( [ [4,'desc'] ] );
                    break;
                }
                
            });                    
        },
    };
    
    TableOrder.init();
})(jQuery);