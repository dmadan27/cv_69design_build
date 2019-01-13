$(document).ready(function () {
init();

console.log('%cDetail Operasional Proyek: ', 'font-style: italic; color: white', listDetail);
	 
	if($('#submit_operasional_proyek').val() == 'action-add') {
		console.log('mode tambah')

		generateID();
		
		$('#id').prop('disabled', true);
		$('.field-id_bank_form').css('display', 'none');

		$('.field-id_proyek_f').css('display', 'none');
		$('.field-id_kas_besar_f').css('display', 'none');
		$('.field-id_distributor_f').css('display', 'none');
		
	} else if($('#submit_operasional_proyek').val() == 'action-edit') {
		console.log('mode edit')
		console.log('%cDetail Yang Akan Di-Delete: ', 'font-style: italic; color: white', toDeleteList)
		getEdit($('#id').val().trim());

		$('.field-id_bank_form').css('display', 'none');
		// $('#id_bank').val();		

	}
		
function init () {
	console.log('%cFunction init run...', 'font-style: blue');

	//Initialize Select2 Elements
	$('#id_proyek').select2({
		placeholder: "Pilih Proyek",
		allowClear: true
	});

	$('#id_bank').select2({
		placeholder: "Pilih Bank",
		allowClear: true
	});

	$('#jenis').select2({
		placeholder: "Pilih Jenis Operasional Proyek",
		allowClear: true
	});

	$('#status').select2({
		placeholder: "Pilih Jenis Pembayaran",
		allowClear: true
	});

	$('#status_lunas').select2({
		placeholder: "Pilih Status Pembayaran",
		allowClear: true
	});

	$('#id_bank_form').select2({
		placeholder: "Pilih Bank",
		allowClear: true
	});

	$('#id_distributor').select2({
		placeholder: "Pilih Distributor",
		allowClear: true,
	});


	 // Inisiasi Function
	 setNamaProyek();
	 setNamaBank();
	 setnamaDistributor();
	 setJenis();
	 setStatus();
	 setStatusLunas();
}

 	//Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true,
      orientation:"bottom auto",
      todayBtn: true,
    });

    // input mask
    $('.input-mask-uang').inputmask({ 
    	alias : 'currency',
    	prefix: '', 
    	radixPoint: ',',
    	digits: 0,
    	groupSeparator: '.', 
    	clearMaskOnLostFocus: true, 
    	digitsOptional: false,
    });

 	// ------------------------------------------------
	// FUNGSI DETAIL OPERSIONAL PROYEK
	// -----------------------------------------------
	
	//tambah detail
	/*
		memanggil modals detail operasional proyek
	*/
    $('#btn_tambahDetail').on('click', function(){
    	resetModal();
    	$('#submit_detail').val('tambah');
    	$('#submit_detail').text('Tambah Detail');

    	$('#modalDetailOperasional').modal();
    });

    $('#form_detail_operasional_proyek').submit(function(e){
    	e.preventDefault();
    	if($('#submit_detail').val() == 'tambah')
    		addDetail();
    	else if($('#submit_detail').val() == 'edit')
    		actionEditDetail();

    	return false;
    });

 	// ------------------------------------------------

    // Submit Operasional Proyek
    $('#form_operasional_proyek').submit(function(e){
    	e.preventDefault();
    	submit();

    	return false;
    });

    // on change field
    $('.field').on('change', function(){
    	if(this.value !== ""){
    		$('.field-'+this.id).removeClass('has-error').addClass('has-success');
    		$(".pesan-"+this.id).text('');
    	}
    	else{
    		$('.field-'+this.id).removeClass('has-error').removeClass('has-success');
    		$(".pesan-"+this.id).text('');
    	}
	});

	resetModal();

	//On Change STATUS & STATUS LUNAS
	$('#status').add('#status_lunas').on('change', function(){

		var status = ($('#status').val() != "" && $('#status').val() != null) ? $('#status').val().trim() : "DEFAULT";
		var status_lunas = ($('#status_lunas').val() != "" && $('#status_lunas').val() != null) ? $('#status_lunas').val().trim() : "DEFAULT";

		console.log('Jenis Pembayaran Changed To - ' + status);
		console.log('Status Pembayaran Changed To - ' + status_lunas);

		//Jika STATUS = "TUNAI" dan STATUS LUNAS = "LUNAS" 
		if(status == "TUNAI" && status_lunas == "LUNAS") {
		
			$('.field-id_bank_form').css('display', 'block');
			$('#btn_tambahDetail').prop('disabled', true);
			$('#submit_operasional_proyek').prop('disabled', false);

		// Jika STATUS = "TUNAI" dan STATUS LUNAS = "BELUM LUNAS"
		} else if(status == "TUNAI" && status_lunas == "BELUM LUNAS"){
		
			$('.field-id_bank_form').css('display', 'none');
			$('#id_bank_form').val(null).trigger('change');
			$('#btn_tambahDetail').prop('disabled', true);
			$('#submit_operasional_proyek').prop('disabled', false);
		
		//Jika STATUS = "KREDIT" dan STATUS LUNAS = "LUNAS"
		} else if(status == "KREDIT" && status_lunas == "LUNAS" && listDetail.length == 0){
			
			$('.field-id_bank_form').css('display', 'none');
			$('#id_bank_form').val(null).trigger('change');
			$('#btn_tambahDetail').prop('disabled', false);

			// $('#id_bank').on('change', function(){
			// 	if($('#id_bank').val() != null){
			// 		$('#btn_tambahDetail').prop('disabled', false);
			// 	} else {
			// 		$('#btn_tambahDetail').prop('disabled', true);
			// 	}
			// });
			
		//Jika STATUS = "KREDIT" dan STATUS LUNAS = "BELUM LUNAS"	
		} else if(status == "KREDIT" && status_lunas == "BELUM LUNAS"){
			
			$('.field-id_bank_form').css('display', 'none');
			$('#id_bank_form').val(null).trigger('change');
			$('#id_bank').prop('disabled', false);
			$('#btn_tambahDetail').prop('disabled', false);
			$('#submit_operasional_proyek').prop('disabled', false);
		
		//Jika STATUS dan STATU LUNAS bernilai "DEFAULT"	
		} else if(status == "DEFAULT" && status_lunas == "DEFAULT"){
			
			$('#id_bank').prop('disabled', true);
			$('#btn_tambahDetail').prop('disabled', true);
			$('#submit_operasional_proyek').prop('disabled', true);
		
		}
	
	});

    $('#id_proyek').on('change', function(){
    	if($('#submit_operasional_proyek').val() == 'action-add')
    		generateID(this.value);
	});
	

 });

// ================ Function detail operasional proyek =================== //

	/**
	*
	*/
	function addDetail(){

		var index = indexDetail++;
			
		var total_detail = ($('#total_detail').inputmask) ? 
			( parseFloat($('#total_detail').inputmask('unmaskedvalue')) ?
				parseFloat($('#total_detail').inputmask('unmaskedvalue')) : 
				$('#total_detail').inputmask('unmaskedvalue')
			) : $('#total_detail').val().trim();

		
			if($('#submit_operasional_proyek').val() == 'action-add'){

				var data = {
					index: index,
					id: '',
					id_operasional_proyek : $('#id').val().trim(),
					id_bank :($('id_bank').val() != "" && $('#id_bank').val() != null) ? $('#id_bank').val().trim() : "",
					nama_bank : $('#id_bank option:selected').text(),
					nama_detail : $('#nama_detail').val().trim(),
					tgl_detail : $('#tgl_detail').val().trim(),
					total_detail : total_detail,
					aksi: 'tambah',
					delete: false,
				};
			
			} else if($('#submit_operasional_proyek').val() == 'action-edit'){

				var data = {
					index: index,
					id: '',
					id_operasional_proyek : $('#id').val().trim(),
					id_bank : ($('id_bank').val() != "" && $('#id_bank').val() != null) ? $('#id_bank').val().trim() : "",
					nama_bank : $('#id_bank option:selected').text(),
					nama_detail : $('#nama_detail').val().trim(),
					tgl_detail : $('#tgl_detail').val().trim(),
					total_detail : total_detail,
					aksi: 'tambah',
					delete: false,
				};

			}
		
		validDetail(data);
		console.log(data);
	}

	/**
	*
	*/
	function validDetail(data, action = 'tambah'){
		$.ajax({
			url: BASE_URL+'operasional-proyek/action-add-detail/',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function(){

			},
			success: function(output){
			console.log('%cResponse validDetail Operasional Proyek: ', 'font-style: italic; color: blue', output);
			console.log(action);
			if(action == 'tambah') {

				if(output.status) {

					// tambah data ke list
					listDetail.push(data);
					listDetail_Tambahan.push(data);
					
					// tambah data ke tabel
					$('#detail_OperasionalproyekTable > tbody:last-child').append(
						"<tr id="+data.index+">"+
							"<td></td>"+
							"<td>"+data.nama_bank+"</td>"+
							"<td>"+data.nama_detail+"</td>"+
							"<td>"+output.data.tgl_detail_full+"</td>"+
							"<td class='text-right'>"+output.data.total_detail_full+"</td>"+
							"<td>"+btnAksi_detail(data.index)+"</td>"+
						"</tr>"
					);
					numbering_listDetail();

					console.log(listDetail);
					console.log(listDetail_Tambahan);

					$("#modalDetailOperasional").modal('hide');
					$('#submit_operasional_proyek').prop('disabled', false);
					resetModal();

				} else {
					
					// decrement index utama
					indexDetail -= 1;
					setError(output.error);	
				}	
			} else if(action == 'edit') {
				
				if(output.status) {

					listDetail[data.index].tgl_detail = data.tgl_detail;
					listDetail[data.index].tgl_detail_full = output.data.tgl_detail_full;
					listDetail[data.index].nama_detail = data.nama_detail;
					listDetail[data.index].id_bank = data.id_bank;
					listDetail[data.index].nama_bank = data.nama_bank;
					listDetail[data.index].total_detail = data.total_detail;
					listDetail[data.index].total_detail_full = output.data.total_detail_full;
					listDetail[data.index].aksi = data.aksi;

					set_data_table(listDetail);

					$("#modalDetailOperasional").modal('hide');
					resetModal();
				}
				else {
					setError(output.error);
				}
			}

		},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log(jqXHR, textStatus, errorThrown);
	            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
	        }
		})
	}


	/**
	*
	*/
	function numbering_listDetail(){
		$('#detail_OperasionalproyekTable tbody tr').each(function(index){
			$(this).children('td:eq(0)').html(index+1);	
		});
	}

	/**
	*
	*/
	function btnAksi_detail(index){
		if($('#status').val() == "TUNAI"){

			var btn_edit = '<button disabled type="button" class="btn btn-success btn-flat btn-sm"'+
					' title="Edit data dari list" onclick="edit_detail('+index+')">'+
					'<i class="fa fa-edit"></i></button>';
			var btn_hapus = '<button disabled type="button" class="btn btn-danger btn-flat btn-sm"'+
					' title="Hapus data dari list" onclick="delete_detail('+index+', this)">'+
					'<i class="fa fa-trash"></i></button>';
			var btn = '<div class="button-group">'+btn_edit+btn_hapus+'</div>';

		} else {

			var btn_edit = '<button type="button" class="btn btn-success btn-flat btn-sm"'+
						' title="Edit data dari list" onclick="edit_detail('+index+')">'+
						'<i class="fa fa-edit"></i></button>';
			var btn_hapus = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
						' title="Hapus data dari list" onclick="delete_detail('+index+', this)">'+
						'<i class="fa fa-trash"></i></button>';
			var btn = '<div class="button-group">'+btn_edit+btn_hapus+'</div>';

		}
		return btn;
	}

	/**
	*
	*/
	function edit_detail(index){
		$('#modalDetailOperasional').modal();
		$('#submit_detail').val('edit');
		$('#submit_detail').text('Edit Detail');

		console.log('edit Clicked on index ' + index);
		setValueDetail(listDetail,index);
	}

	/**
	*
	*/
	function actionEditDetail(){

		var aksi = ($('#submit_proyek').val() == 'action-add') ? 'tambah' : 'edit';

		var total_detail = ($('#total_detail').inputmask) ? 
			( parseFloat($('#total_detail').inputmask('unmaskedvalue')) ?
				parseFloat($('#total_detail').inputmask('unmaskedvalue')) : 
				$('#total_detail').inputmask('unmaskedvalue')
			) : $('#total_detail').val().trim();

		var data = {
			index: indexDetail,
			id: $('#id_detail').val().trim(),
			id_bank: $('#id_bank').val().trim(),
			nama_bank: $('#id_bank option:selected').text(),
			tgl_detail : $('#tgl_detail').val().trim(),
			nama_detail : $('#nama_detail').val().trim(),
			total_detail : total_detail,
			total_detail_full : '',
			aksi: aksi,
			delete: false,
		};

		console.log('Ini data','',data)

		// validDetail(data, 'edit');

	}

	/**
	*
	*/
	function setValueDetail(data,index){

		console.log('%cButton Edit Detail Pembayaran Proyek Clicked...', 'font-style: italic');

		resetModal();

		// load data ke modal
		var data = listDetail[index];
		
		var bank = (data.id_bank != "" && data.id_bank != null) ? data.id_bank : false;
		if(bank) { $('#id_bank').val(bank).trigger('change'); }

		indexDetail = data.index;
		$('#id_detail').val(data.id);
		$('#tgl_detail').val(data.tgl_detail);
		$('#nama_detail').val(data.nama_detail);
		$('#total_detail').val(data.total_detail);

		$('#modalDetailOperasonal').modal();

	}

	/**
	*
	*/
	function delete_detail(index, val){
		$(val).parent().parent().parent().remove();
		listDetail[index].delete = true;
		if(listDetail.length == 0 && $('#status').val() != "KREDIT" && $('#status_lunas').val() != "BELUM LUNAS"){
			$('#submit_operasional_proyek').prop('disabled', true);
		}
		numbering_listDetail();
		console.log('delete Clicked on index ' + index);
		console.log('%cList Detail Ready: ', 'font-style: italic; color: white', listDetail);
	}	

	/**
	*
	*/
	function set_data_table(data){
		console.log('%cDetail Operasional Proyek Setelah Di Edit: ', 'font-style: italic; color: white', data);
		$('#detail_OperasionalproyekTable tbody tr').remove();

		$.each(data, function(i, item){
			$('#detail_OperasionalproyekTable > tbody:last-child').append(
				'<tr>'+
					'<td></td>'+
					'<td>'+item.nama_bank+'</td>'+
					'<td>'+item.nama_detail+'</td>'+
					'<td>'+item.tgl_detail_full+'</td>'+
					'<td class="text-right">'+item.total_detail_full+'</td>'+
					'<td>'+btnAksi_detail(item.index)+'</td>'+
				'</tr>'
			);
		});
		
		numbering_listDetail();	
	}

// ========================================================================= //

function setNamaProyek_new($id = false){
	var id = "";
	if ($('#submit_operasional_proyek').val() == 'action-edit') id = $('#id').val().split('-')[1];

	$.ajax({
		url: BASE_URL+'operasional-proyek/get-nama-proyek/'+id,
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			console.log("Response setNamaProyek: ", data);
			$.each(data, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#id_proyek').append(newOption);
			});

			if(id == "") $('#id_proyek').val(null);
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log("Error Response from setNamaProyek: ",jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})		
}

function setNamaProyek(){
	$.ajax({
		url: BASE_URL+'operasional-proyek/get-nama-proyek',
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			$.each(data, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#id_proyek').append(newOption);
			});
			$('#id_proyek').val(null);
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})		

}

function setNamaBank(){
	$.ajax({
		url: BASE_URL+'operasional-proyek/get-nama-bank',
		type: 'post',
		dataType: 'json',
		beforeSend: function(){},
		success: function(response){
			console.log('%cResponse setNamaBank Operasional Proyek: ', 'font-style: italic', response);
			$.each(response, function(index, item){
				var newOption = new Option(item.text, item.id);
				var newOption2 = new Option(item.text, item.id);
				$('#id_bank').append(newOption).trigger('change');
				$('#id_bank_form').append(newOption2).trigger('change');
			});
			// $('#id_bank').val(null).trigger('change');
			$('#id_bank_form').val(null).trigger('change');
			
		},
		error: function (jqXHR, textStatus, errorThrown){
            console.log('%cResponse Error setBank Proyek: ', 'color: red; font-style: italic', jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
	console.log($('#id_bank').val());
}

function setnamaKasBesar(){
	// if ($('#submit_operasional_proyek').val() == 'action-edit') id = $('#id_kas_besar').val().split('-')[1];
	$.ajax({
		url: BASE_URL+'operasional-proyek/get-nama-kas-besar',
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			console.log("Response setnamaKasBesar: ",data);
			$.each(data, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#id_kas_besar').append(newOption);
			});
			$('#id_kas_besar').val(null);
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log("Error Response setnamaKasBesar : ",jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})		

}

function setnamaDistributor(){
	$.ajax({
		url: BASE_URL+'operasional-proyek/get-nama-distributor',
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			console.log('%cResponse setNamaDistributor Operasional Proyek: ', 'font-style: italic', data);
			$.each(data, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#id_distributor').append(newOption).trigger('change');
			});
			$('#id_distributor').val(null).trigger('change');
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})		


}

/**
*
*/
function getDataForm(){

	var data = new FormData()

	var total = ($('#total').inputmask) ? 
		( parseFloat($('#total').inputmask('unmaskedvalue')) ?
			parseFloat($('#total').inputmask('unmaskedvalue')) : 
			$('#total').inputmask('unmaskedvalue')
		) : $('#total').val().trim();

	var bank = '';

	

	if($('#submit_operasional_proyek').val().trim().toLowerCase() == 'action-edit'){

		if($('#status').val() == "TUNAI" && $('#status_lunas').val() == "BELUM LUNAS"){
		
			bank = '';
		
		} else if($('#status').val() == "TUNAI" && $('#status_lunas').val() == "LUNAS") {
		
			bank = ($('#id_bank_form').val() != "" && $('#id_bank_form').val() != null) ? $('#id_bank_form').val().trim() : ""; 
			// $('#id_bank_form').val().trim();
		
		} else if (($('#status').val() == "KREDIT" && $('#status_lunas').val() == "BELUM LUNAS" || $('#status_lunas').val() == "LUNAS")) {
			
			bank = $('#id_bank').val().trim();
		
		}

		var dataOperasionalProyek = {
			id : $('#id').val().trim(),
			id_proyek : $('#id_proyek_f').val().trim(),
			id_bank : bank,
			id_distributor : $('#id_distributor_f').val().trim(),
			tgl : $('#tgl').val().trim(),
			nama : $('#nama').val().trim(),
			jenis : $('#jenis').val().trim(),
			total : total,
			sisa : '0',
			status : $('#status').val().trim(),
			status_lunas : $('#status_lunas').val().trim(),
			ket : $('#ket').val().trim()
		}
		console.log('proses edit')
		var detailList = listDetail
		var detailTambahan = listDetail_Tambahan
	
	} else {

		if($('#status').val() == "TUNAI" && $('#status_lunas').val() == "BELUM LUNAS"){
			bank = '';
		} else if (($('#status').val() == "KREDIT" && $('#status_lunas').val() == "BELUM LUNAS" || $('#status_lunas').val() == "LUNAS")) {
			bank = $('#id_bank').val().trim();
		} else {
			bank = ($('id_bank_form').val() != "" && $('#id_bank_form').val() != null) ? $('#id_bank_form').val().trim() : ""; 
		}

		var id_distributor = ($('#id_distributor').val() != "" && $('#id_distributor').val() != null) ? $('#id_distributor').val().trim() : "";
		var id_proyek = ($('#id_proyek').val() != "" && $('#id_proyek').val() != null) ? $('#id_proyek').val().trim() : "";
		var jenis = ($('#jenis').val() != "" && $('#jenis').val() != null) ? $('#jenis').val().trim() : "";
		var status = ($('#status').val() != "" && $('#status').val() != null) ? $('#status').val().trim() : "";
		var status_lunas = ($('#status_lunas').val() != "" && $('#status_lunas').val() != null) ? $('#status_lunas').val().trim() : "";
	
		var dataOperasionalProyek = {
			id : $('#id').val().trim(),
			id_proyek : id_proyek,
			id_bank : bank,
			id_distributor : id_distributor, 
			tgl : $('#tgl').val().trim(),
			nama : $('#nama').val().trim(),
			jenis : jenis,
			total : total,
			sisa : '0',
			status : status,
			status_lunas : status_lunas,
			ket : $('#ket').val().trim()
		}
		console.log('proses add')
		var detailList = listDetail
		var detailTambahan = listDetail_Tambahan
	}

	// // data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('dataOperasionalProyek', JSON.stringify(dataOperasionalProyek));
	data.append('listDetail', JSON.stringify(detailList));
	data.append('listDetail_Tambahan', JSON.stringify(detailTambahan));
	data.append('toDelete',JSON.stringify(toDeleteList));
	data.append('toEdit', JSON.stringify(toEditList));
	data.append('action', $('#submit_operasional_proyek').val().trim());
	
	return data;
}

/**
*
*/
function submit(){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'operasional-proyek/'+$('#submit_operasional_proyek').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache :false,
		processData: false,
		beforeSend: function(){
			$('#submit_operasional_proyek').prop('disabled', true);
			$('#submit_operasional_proyek').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status){
				$('#submit_operasional_proyek').prop('disabled', false);
				$('#submit_operasional_proyek').html($('#submit_operasional_proyek').text());
				setError(output.error);
				setNotif(output.notif.default);
			
			}
			else window.location.href = BASE_URL+'operasional-proyek/';
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#submit_operasional_proyek').prop('disabled', false);
			$('#submit_operasional_proyek').html($('#submit_operasional_proyek').text());
		}

	})
}

/**
*
*/
function getEdit(id){

	$.ajax({
		url: BASE_URL+'operasional-proyek/get-edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){},
		success: function(output){
			console.log('%cResponse getEdit Operasional Proyek: ', 'font-style: italic', output.dataOperasionalProyek);

			var bank = (output.dataOperasionalProyek.id_bank != "" && output.dataOperasionalProyek.id_bank != null) ? output.dataOperasionalProyek.id_bank : false;
			if(bank) { $('#id_bank_form').val(bank).trigger('change'); }

			$('#id').prop('disabled', true);
			$('.field-id_kas_besar').css('display', 'none');
			$('.field-id_distributor').css('display', 'none');
			$('.field-id_proyek').css('display', 'none');
			
			$('#id_proyek_f').prop('disabled', true);
			$('#id_kas_besar_f').prop('disabled', true);
			$('#id_distributor_f').prop('disabled', true);

			$('.field-id_bank_f').css('display', 'block');
			$('.field-id_kas_besar_f').css('display', 'block');
			$('.field-id_distributor_f').css('display', 'block');

			$("#jenis").val(output.dataOperasionalProyek.jenis).trigger('change');
			$("#status").val(output.dataOperasionalProyek.status).trigger('change');
			$("#status_lunas").val(output.dataOperasionalProyek.status_lunas).trigger('change');

			

			$.each(output.dataDetail, function(i, data){
				
				var detailOperasional = {
					index: indexDetail++,
					id: data.id,
					id_operasional_proyek: data.id_operasional_proyek,
					id_bank: data.id_bank,
					nama_bank: data.nama_bank,
					nama_detail: data.nama_detail,
					tgl_detail: data.tgl_detail,
					tgl_detail_full: data.tgl_detail_full,
					total_detail: data.total_detail,
					total_detail_full: data.total_detail_full,
					aksi: 'edit',
					delete : false
				};

			renderTableDetailOperasional(detailOperasional)

			});

		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function renderTableDetailOperasional(data){
	// tambah data ke tabel

	$('#detail_OperasionalproyekTable > tbody:last-child').append(
		"<tr id="+data.index+">"+
			"<td></td>"+
			"<td>"+data.nama_bank+"</td>"+
			"<td>"+data.nama_detail+"</td>"+
			"<td>"+data.tgl_detail_full+"</td>"+
			"<td class='text-right'>"+data.total_detail_full+"</td>"+
			"<td>"+btnAksi_detail(data.index)+"</td>"+
		"</tr>"
	);
	// console.log(data)
	listDetail.push(data)
	numbering_listDetail();
	
}

/**
*
*/
function setError(error){
	$.each(error, function(index, item){
		// console.log(index);

		if(item != ""){
			$('.field-'+index).removeClass('has-success').addClass('has-error');
			$('.pesan-'+index).text(item);
		}
		else{
			$('.field-'+index).removeClass('has-error').addClass('has-success');
			$('.pesan-'+index).text('');	
		}
	});
}



/**
*
*/
function generateID(proyek = null){
	$.ajax({
		url: BASE_URL+'operasional-proyek/get-last-id/',
		type: 'post',
		dataType: 'json',
		data: {
			// token: $('#token').val().trim()
			'get_proyek': proyek,
		},
		beforeSend: function(){},
		success: function(output){
			console.log(output);
			$('#id').val(output);	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function setJenis(){
	var jenis = [
		{value: "TEKNIS", text: "TEKNIS"},
		{value: "NON-TEKNIS", text: "NON-TEKNIS"},
	];

	$.each(jenis, function(index, item){
		var option = new Option(item.text, item.value);
		$("#jenis").append(option);
	});
	$("#jenis").val(null).trigger('change');
}

/**
*
*/
function setStatus(){
	var status = [
		{value: "TUNAI", text: "TUNAI"},
		{value: "KREDIT", text: "KREDIT"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
	$("#status").val(null).trigger('change');
}
/**
*
*/
function setStatusLunas(){
	var status_lunas = [
		{value: "LUNAS", text: "LUNAS"},
		{value: "BELUM LUNAS", text: "BELUM LUNAS"},
	];

	$.each(status_lunas, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status_lunas").append(option);
	});
	$("#status_lunas").val(null).trigger('change');
}

/**
*
*/
function resetForm(){
	// trigger reset form
	$('#form_detail_operasional_proyek').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}

/**
*
*/
function resetModal(){
	$('#form_detail_operasional_proyek').trigger('reset');
	// $('modalDetailOperasional').trigger('reset');

	// hapus semua pesan
	$('#form_detail_operasional_proyek .pesan').text('');

	// hapus semua feedback
	$('#form_detail_operasional_proyek .form-group').removeClass('has-success').removeClass('has-error');
}