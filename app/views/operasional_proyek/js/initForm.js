$(document).ready(function () {


	if($('#submit_operasional_proyek').val() == 'action-add') {
		generateID();
		$('#id').prop('disabled', true);
		$('#id_bank').prop('disabled', true);
		$('#btn_tambahDetail').prop('disabled', true);
		// $('#submit_operasional_proyek').prop('disabled', true);
		$('#id_bank').prop('disabled', true);
	    //Initialize Select2 Elements
	    $('#id_proyek').select2({
	    	placeholder: "Pilih Proyek",
			allowClear: true
	    });

	    // set format 
	    // $('#id_bank').prop('', true);
	    //Initialize Select2 Elements
	    $('#id_bank').select2({
	    	placeholder: "Pilih Bank",
			allowClear: true
	    });

	    $('#id_kas_besar').select2({
	    	placeholder: "Pilih Kas Besar",
			allowClear: true
	    });

	    $('#id_distributor').select2({
	    	placeholder: "Pilih Distributor",
			allowClear: true
	    });
	}
	else if($('#submit_operasional_proyek').val() == 'action-edit') {
		getEdit($('#id').val().trim());
		// $('#id_bank').val();		

	}
		
    
    // Inisiasi Function
    setNamaProyek();
    setNamaBank();
    setnamaKasBesar();
	setnamaDistributor();
	setJenis();
	setStatus();
	setStatusLunas();


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

    //Flat red color scheme for iCheck
    // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    //   checkboxClass: 'icheckbox_flat-green',
    //   radioClass   : 'iradio_flat-green'
    // });

 	// ------------------------------------------------
	// FUNGSI DETAIL OPERSIONAL PROYEK
	// -----------------------------------------------
	
	//tambah detail
		/*
			memanggil modals detail operasional proyek
		*/
    $('#btn_tambahDetail').on('click', function(){
    	console.log(listDetail);
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

	//On Change STATUS & STATUS LUNAS
	$('#status').add('#status_lunas').on('change', function(){
		console.log($('#status').val().trim());
		console.log($('#status_lunas').val().trim());

		//Jika STATUS = "TUNAI" dan STATUS LUNAS = "LUNAS" 
		if($('#status').val() == "TUNAI" && $('#status_lunas').val() == "LUNAS") {
		
			$('#id_bank').prop('disabled', false);
			$('#btn_tambahDetail').prop('disabled', true);
			$('#submit_operasional_proyek').prop('disabled', false);

		//Jika STATUS = "TUNAI" dan STATUS LUNAS = "BELUM LUNAS"
		} else if($('#status').val() == "TUNAI" && $('#status_lunas').val() == "BELUM LUNAS"){
		
			$('#id_bank').prop('disabled', true);
			$('#btn_tambahDetail').prop('disabled', true);
			$('#submit_operasional_proyek').prop('disabled', false);
		
		//Jika STATUS = "KREDIT" dan STATUS LUNAS = "LUNAS"
		} else if($('#status').val() == "KREDIT" && $('#status_lunas').val() == "LUNAS" && listDetail.length == 0){
			
			$('#id_bank').prop('disabled', false);
			$('#submit_operasional_proyek').prop('disabled', true);
			$('#btn_tambahDetail').prop('disabled', true);

			$('#id_bank').on('change', function(){
				if($('#id_bank').val() != null){
					$('#btn_tambahDetail').prop('disabled', false);
				} else {
					$('#btn_tambahDetail').prop('disabled', true);
				}
			});
			
		//Jika STATUS = "KREDIT" dan STATUS LUNAS = "BELUM LUNAS"	
		} else if($('#status').val() == "KREDIT" && $('#status_lunas').val() == "BELUM LUNAS"){
			
			$('#id_bank').prop('disabled', false);
			$('#btn_tambahDetail').prop('disabled', false);
			$('#submit_operasional_proyek').prop('disabled', false);
		
		//Jika STATUS dan STATU LUNAS bernilai "DEFAULT"	
		} else if($('#status').val() == "DEFAULT" && $('#status_lunas').val() == "DEFAULT"){
			
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

		 $('#id_bank').select2({
	    	placeholder: "Pilih Bank",
			allowClear: true
	    });

		var index = indexDetail++;

		// var total_detail = parseFloat($('#total_detail').val().trim()) ? 
		// 	parseFloat($('#total_detail').val().trim()) : $('#total_detail').val().trim();
			
		var total_detail = ($('#total_detail').inputmask) ? 
			( parseFloat($('#total_detail').inputmask('unmaskedvalue')) ?
				parseFloat($('#total_detail').inputmask('unmaskedvalue')) : 
				$('#total_detail').inputmask('unmaskedvalue')
			) : $('#total_detail').val().trim();

		

		var data = {
			index: index,
			id: '',
			id_operasional_proyek : $('#id').val().trim(),
			id_bank : $('#id_bank').val().trim(),
			nama_detail : $('#nama_detail').val().trim(),
			tgl_detail : $('#tgl_detail').val().trim(),
			total_detail : total_detail,
			
			aksi: 'tambah',
			delete: false,
		};

		validDetail(data);
		console.log(data);
		// console.log('Index : '+index);
		// console.log('Index Utama: '+indexDetail);
	}

	/**
	*
	*/
	function validDetail(data){
		$.ajax({
			url: BASE_URL+'operasional-proyek/action-add-detail/',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function(){

			},
			success: function(output){
				console.log(output);
				if(output.status){
					// tambah data ke list
					listDetail.push(data);
					
					// tambah data ke tabel
					$('#detail_OperasionalproyekTable > tbody:last-child').append(
						"<tr id="+data.index+">"+
							"<td></td>"+
							"<td>"+data.id_bank+"</td>"+
							"<td>"+data.nama_detail+"</td>"+
							"<td>"+data.tgl_detail+"</td>"+
							"<td>"+data.total_detail+"</td>"+
							// "<td></td>"+
							"<td>"+btnAksi_detail(data.index)+"</td>"+
						"</tr>"
					);
					numbering_listDetail();
					// console.log(listDetail);

					$("#modalDetailOperasional").modal('hide');
					$('#submit_operasional_proyek').prop('disabled', false);
					resetModal();
				}
				else{
					// decrement index utama
					indexDetail -= 1;
					setError(output.error);
				}	
				console.log(listDetail);
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
		var btn_edit = '<button type="button" class="btn btn-success btn-flat btn-sm"'+
					' title="Edit data dari list" onclick="edit_detail('+index+')">'+
					'<i class="fa fa-edit"></i></button>';
		var btn_hapus = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
					' title="Hapus data dari list" onclick="delete_detail('+index+')">'+
					'<i class="fa fa-trash"></i></button>';

		var btn = '<div class="button-group">'+btn_edit+btn_hapus+'</div>';

		return btn;
	}

	/**
	*
	*/
	function edit_detail(index){
		$('#modalDetailOperasional').modal();
		$('#submit_detail').val('edit');
		$('#submit_detail').text('Edit Detail');

		setValueDetail(listDetail,index);
		// console.log('trigger edit');
	}

	/**
	*
	*/
	function actionEditDetail(){
		var qty_detail = parseFloat($('#qty_detail').val().trim()) ? 
			parseFloat($('#qty_detail').val().trim()) : $('#qty_detail').val().trim();

		var harga_detail = parseFloat($('#harga_detail').val().trim()) ? 
			parseFloat($('#harga_detail').val().trim()) : $('#harga_detail').val().trim();

		var sub_total_detail = parseFloat($('#sub_total_detail').val().trim()) ? 
			parseFloat($('#sub_total_detail').val().trim()) : $('#sub_total_detail').val().trim();

		var harga_asli_detail = parseFloat($('#harga_asli_detail').val().trim()) ? 
			parseFloat($('#harga_asli_detail').val().trim()) : $('#harga_asli_detail').val().trim();
		
		var sisa_detail = parseFloat($('#sisa_detail').val().trim()) ? 
			parseFloat($('#sisa_detail').val().trim()) : $('#sisa_detail').val().trim();

		var data = {
			index: $('#id_detail').val().trim(),
			id: '',
			id_operasional_proyek : $('#id').val().trim(),
			nama_detail : $('#nama_detail').val().trim(),
			jenis_detail : $('#jenis_detail').val().trim(),
			satuan_detail : $('#satuan_detail').val().trim(),
			qty_detail : qty_detail,
			harga_detail : harga_detail,
			sub_total_detail : sub_total_detail,
			status_detail : $('#status_detail').val().trim(),
			harga_asli_detail : harga_asli_detail,
			sisa_detail : sisa_detail,
			status_lunas_detail : $('#status_lunas_detail').val().trim(),
			aksi: 'tambah',
			delete: false,
		};

		$.each(listDetail, function(i,item){
			if(i == data.index){
				listDetail[i] = data;
			}
		});

		$('#modalDetailOperasional').modal('hide');
		set_data_table(listDetail);
		console.log(listDetail);
	}

	/**
	*
	*/
	function delete_detail(index){
		
		listDetail.splice(index, 1);
		var indexval = index;
		$('#'+indexval).remove()
		
		if(listDetail.length == 0){
			$('#submit_operasional_proyek').prop('disabled', true);
		}
		
		console.log('delete Clicked on index ' + index);
		console.log(listDetail);
	}

	/**
	*
	*/
	function setValueDetail(data,index){

		
		$('#id_detail').val(index);
		$('#id_bank').val(data[index].id_bank);
		$('#nama_detail').val(data[index].nama_detail);
		$('#tgl_detail').val(data[index].tgl_detail);
		$('#total_detail').val(data[index].total_detail);
		
		// $('#satuan_detail').val(data[index].satuan_detail);
		// $('#qty_detail').val(data[index].qty_detail);
		// $('#harga_detail').val(data[index].harga_detail);
		// $('#sub_total_detail').val(data[index].sub_total_detail);
		// $('#status_detail').val(data[index].status_detail);
		// $('#harga_asli_detail').val(data[index].harga_asli_detail);
		// $('#sisa_detail').val(data[index].sisa_detail);
		// $('#status_lunas_detail').val(data[index].status_lunas_detail);
	}

	/**
	*
	*/
	function set_data_table(data){
		$('#detail_OperasionalproyekTable tbody tr').remove();

		$.each(data, function(i, item){
			$('#detail_OperasionalproyekTable > tbody:last-child').append(
				'<tr>'+
					'<td></td>'+
					'<td>'+item.id_bank+'</td>'+
					'<td>'+item.nama_detail+'</td>'+
					'<td>'+item.tgl_detail+'</td>'+
					'<td>'+item.total_detail+'</td>'+
					// '<td>'+item.qty_detail+'</td>'+
					// '<td>'+item.harga_detail+'</td>'+
					// '<td>'+item.sub_total_detail+'</td>'+
					// '<td>'+item.status_detail+'</td>'+
					// '<td>'+item.harga_asli_detail+'</td>'+
					// '<td>'+item.sisa_detail+'</td>'+
					// '<td>'+item.status_lunas_detail+'</td>'+
					// '<td></td>'+
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
			console.log(data);
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
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			console.log(data);
			$.each(data, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#id_bank').append(newOption).trigger('change');
			});
			$('#id_bank').val(null).trigger('change');
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})			

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
			console.log(data);
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
	console.log(listDetail)
	var data = new FormData()
	// var total = parseFloat($('#total').val().trim()) ? parseFloat($('#total').val().trim()) : $('#total').val().trim();

	var total = ($('#total').inputmask) ? 
		( parseFloat($('#total').inputmask('unmaskedvalue')) ?
			parseFloat($('#total').inputmask('unmaskedvalue')) : 
			$('#total').inputmask('unmaskedvalue')
		) : $('#total').val().trim();

	var bank = '';

	if($('#status').val() == "TUNAI" && $('#status_lunas').val() == "BELUM LUNAS"){
		bank = '';
	} else {
		bank = $('#id_bank').val().trim();
	}

	// if($('#submit_operasional_proyek').val().trim().toLowerCase() == 'action-edit'){
	// 	id : $('#id').val().trim(),
	// 	id_proyek : $('#id_proyek').val().trim(),
	// 	id_bank : $('#id_bank').val().trim(),
	// 	id_kas_besar : $('#id_kas_besar').val().trim(),
	// 	id_distributor : $('#id_distributor').val().trim(),
	// 	tgl : $('#tgl').val().trim(),
	// 	nama : $('#nama').val().trim(),
	// 	jenis : $('#jenis').val().trim(),
	// 	total : total,
	// 	sisa : sisa,
	// 	status : $('#status').val().trim(),
	// 	status_lunas : $('#status_lunas').val().trim(),
	// 	ket : $('#ket').val().trim()
	// }

	var dataOperasionalProyek = {
		id : $('#id').val().trim(),
		id_proyek : $('#id_proyek').val().trim(),
		id_bank : bank,
		id_kas_besar : $('#id_kas_besar').val().trim(),
		id_distributor : $('#id_distributor').val().trim(),
		tgl : $('#tgl').val().trim(),
		nama : $('#nama').val().trim(),
		jenis : $('#jenis').val().trim(),
		total : total,
		sisa : '0',
		status : $('#status').val().trim(),
		status_lunas : $('#status_lunas').val().trim(),
		ket : $('#ket').val().trim()
	}

	var detailList = listDetail

	// // data.append('token', $('#token_form').val().trim());
	// // data.append('dataOperasionalProyek', JSON.stringify(dataOperasionalProyek));
	data.append('id', $('#id').val().trim());
	data.append('dataOperasionalProyek', JSON.stringify(dataOperasionalProyek));
	data.append('listDetail', JSON.stringify(detailList));
	data.append('action', $('#submit_operasional_proyek').val().trim());
	
	return data;
}

/**
*
*/ 
function validData() {
	
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
				// toastr.warning(output.notif.message, output.notif.title);
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


	// $('#id').prop('disabled', true);
		// $('#id_proyek').prop('disabled', true);
		// $('.field-id_bank').css('display', 'none');
		// $('.field-id_kas_besar').css('display', 'none');
		// $('.field-id_distributor').css('display', 'none');

	$.ajax({
		url: BASE_URL+'operasional-proyek/get-edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){},
		success: function(output){
			console.log(output);

			// each dataOperasionalProyek
			// $.each(output.dataOperasionalProyek, function(i, data)
					// var dataOperasionalProyek = {
					// index: index,
					// id: data.id,
					// id_proyek: data.id_proyek,
					// id_bank: data.id_bank,
					// id_kas_besar: data.id_kas_besar,
					// id_distributor: data.id_distributor,
					// tgl: data.tgl,
					// nama: data.nama,
					// id_bank: data.id_bank,
					// id_bank: data.id_bank,
					// id_bank: data.id_bank,
					// id_bank: data.id_bank,
					// id_bank: data.id_bank,
					// id_bank: data.id_bank,
				// 	aksi: 'edit',
				// 	delete: false,
				// };
				
			
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
		{value: "DEFAULT", text: "PILIH"},
		{value: "TEKNIS", text: "TEKNIS"},
		{value: "NON-TEKNIS", text: "NON-TEKNIS"},
	];

	$.each(jenis, function(index, item){
		var option = new Option(item.text, item.value);
		$("#jenis").append(option);
	});
}

/**
*
*/
function setStatus(){
	var status = [
		{value: "DEFAULT", text: "PILIH"},
		{value: "TUNAI", text: "TUNAI"},
		{value: "KREDIT", text: "KREDIT"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
}
/**
*
*/
function setStatusLunas(){
	var status_lunas = [
		{value: "DEFAULT", text: "PILIH"},
		{value: "LUNAS", text: "LUNAS"},
		{value: "BELUM LUNAS", text: "BELUM LUNAS"},
	];

	$.each(status_lunas, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status_lunas").append(option);
	});
}

/**
*
*/
// function resetForm(){
// 	// trigger reset form
// 	$('#form_detail_operasional_proyek').trigger('reset');

// 	// hapus semua pesan
// 	$('.pesan').text('');

// 	// hapus semua feedback
// 	$('.form-group').removeClass('has-success').removeClass('has-error');
// }

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