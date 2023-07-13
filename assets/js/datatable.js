
$(document).ready(function () {
    
    /*-------Configurar tablas---------*/
    var table = $('table').DataTable( {
        dom: 'Bfrtip',
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

});
