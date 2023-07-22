/**
 * Javascript Datatable init
 */

var table;
var handleDataTable = function () {
    /*-------Table---------*/
     table = $('table').DataTable({
         dom: 'Bfrtip',
         columns: getColumnsFromTable('table'),
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5'
        ],
        language: {
            lengthMenu: 'Mostrando _MENU_ registros por página',
            zeroRecords: 'No hay datos',
            info: 'Página _PAGE_ de _PAGES_',
            infoEmpty: 'No hay datos',
            infoFiltered: '(Filtrado de _MAX_ registros)',
            "paginate": {
                "first": "Primera",
                "next": "Siguiente",
                "previous": "Anterior",
                "last": "última"
            },
            "loadingRecords": "Cargando...",
            "processing":     "",
            "search":         "Buscar",
        },
        responsive: true,
    });
    
    $('table tfoot th').each( function () {
    var title = $('table thead th').eq( $(this).index() ).text();
        $(this).html('<input type="text" placeholder="Buscar ' + title + '" />');
        
        table.columns().every( function () {
            var column = this;
        
            $( 'input', this.footer() ).on( 'keyup change', function () {
                column
                    .search( this.value )
                    .draw();
            } );
        });
    });
    
    /*----------------*/
}

var TableManage = function () {
	"use strict";
	return {
		init: function () {
			handleDataTable();
		}
	};
}();

function getColumnsFromTable(tableSelector) {
    var columns = [];
    $(tableSelector + ' thead th').each(function() {
        var columnName = $(this).text().trim();
        var columnData = { data: columnName };
        columns.push(columnData);
    });
    return columns;
}

$(document).ready(function () {
    TableManage.init();
});

