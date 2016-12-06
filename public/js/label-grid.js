var table;

$(function() {
    table = $("#label_table").DataTable({
        "bProcessing": false,
        "bDeferRender": true,
        "aLengthMenu": [10,20,50,100],
        "aoColumns": [
                      { "sType": "string" },
                      { "sType": "string" },
                      { "sType": "string" },
                      { "sType": "string", "bSortable": false },
                      { "sType": "string", "bSortable": false },
                      { "className":'details-control',
                          "orderable": false,
                          "data": null,
                          "defaultContent": ''
                      }
                     ],
        "bPaginate":true,
        "sPaginationType": "full_numbers",
        "iDisplayLength": 2,
        "bJQueryUI": true,
        "bInfo": true,
        "aaSorting":[],
        "bServerSide": true,
        "sAjaxSource": "label/ajaxLabels",
        "fnServerData": function ( sSource, aaData, fnCallback ) {
            $.ajax( {
                "dataType": 'array',
                "type": "POST",
                "url": sSource,
                "data": aaData,
                "success": function(data) {
                    fnCallback(data);
                }
            } );
        }
    });
});