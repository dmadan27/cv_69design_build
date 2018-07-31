$(function(){
	$('#mutasi_saldo_kas_kecilTable').DataTable();

});

	// btn Export
    $('#exportExcel').on('click', function(){
        // if(this.value.trim() != "") 
            window.location.href = BASE_URL+'saldo-kas-kecil/export/';
       
    });