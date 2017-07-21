 $(document).ready( function () {
    //Da la apariencia del css datatable
    $('#tblLista').DataTable();    

    // evento click
    $('.modificar').click( function(){
        var idtd = $(this).parents("tr").find("td").eq(0).text();
        location.href='visitante.php?MOD='+idtd;
    });

});  // fin document ready.
