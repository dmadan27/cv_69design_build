$(document).ready(function () {
	// setStatusDetail();
	
	if($('#submit_operasional_proyek').val() == 'action-add') generateID();
	// else if($('#submit_operasional_proyek').val() == 'action-edit') getEdit($('#id').val().trim());
		
	$('#id').prop('disabled', true);
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

    setNamaProyek();
    setNamaBank();

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
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });

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
			index: index,
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
						'<tr>'+
							'<td></td>'+
							'<td>'+data.nama_detail+'</td>'+
							'<td>'+data.jenis_detail+'</td>'+
							'<td>'+data.satuan_detail+'</td>'+
							'<td>'+data.qty_detail+'</td>'+
							'<td>'+data.harga_detail+'</td>'+
							'<td>'+data.sub_total_detail+'</td>'+
							'<td>'+data.status_detail+'</td>'+
							'<td>'+data.harga_asli_detail+'</td>'+
							'<td>'+data.sisa_detail+'</td>'+
							'<td>'+data.status_lunas_detail+'</td>'+
							// '<td></td>'+
							'<td>'+btnAksi_detail(data.index)+'</td>'+
						'</tr>'
					);
					numbering_listDetail();
					console.log(listDetail);

					$("#modalDetailOperasional").modal('hide');
					resetModal();
				}
				else{
					// decrement index utama
					indexDetail -= 1;
					setError(output.error);
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
		$('#modalDetailOperasional').modal();
	}

	/**
	*
	*/
	function setValueDetail(data,index){
		$('#id_detail').val(index);
		$('#nama_detail').val(data[index].nama_detail);
		$('#jenis_detail').val(data[index].jenis_detail);
		$('#satuan_detail').val(data[index].satuan_detail);
		$('#qty_detail').val(data[index].qty_detail);
		$('#harga_detail').val(data[index].harga_detail);
		$('#sub_total_detail').val(data[index].sub_total_detail);
		$('#status_detail').val(data[index].status_detail);
		$('#harga_asli_detail').val(data[index].harga_asli_detail);
		$('#sisa_detail').val(data[index].sisa_detail);
		$('#status_lunas_detail').val(data[index].status_lunas_detail);
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
					'<td>'+item.nama_detail+'</td>'+
					'<td>'+item.jenis_detail+'</td>'+
					'<td>'+item.satuan_detail+'</td>'+
					'<td>'+item.qty_detail+'</td>'+
					'<td>'+item.harga_detail+'</td>'+
					'<td>'+item.sub_total_detail+'</td>'+
					'<td>'+item.status_detail+'</td>'+
					'<td>'+item.harga_asli_detail+'</td>'+
					'<td>'+item.sisa_detail+'</td>'+
					'<td>'+item.status_lunas_detail+'</td>'+
					// '<td></td>'+
					'<td>'+btnAksi_detail(item.index)+'</td>'+
				'</tr>'
			);
		});
		
		numbering_listDetail();	
	}

// ========================================================================= //

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

/**
*
*/
function getDataForm(){
	var data = new FormData();

	// var total = parseFloat($('#total').val().trim()) ? parseFloat($('#total').val().trim()) : $('#total').val().trim();

	var total = ($('#total').inputmask) ? 
		( parseFloat($('#total').inputmask('unmaskedvalue')) ?
			parseFloat($('#total').inputmask('unmaskedvalue')) : 
			$('#total').inputmask('unmaskedvalue')
		) : $('#total').val().trim();

	var dataOperasionalProyek = {
		id : $('#id').val().trim(),
		id_proyek : $('#id_proyek').val().trim(),
		id_bank : $('#id_bank').val().trim(),
		tgl : $('#tgl').val().trim(),
		nama : $('#nama').val().trim(),
		total : total
	}

	// data.append('token', $('#token_form').val().trim());
	// data.append('dataOperasionalProyek', JSON.stringify(dataOperasionalProyek));
	data.append('id', $('#id').val().trim());
	data.append('dataOperasionalProyek', JSON.stringify(dataOperasionalProyek));
	data.append('dataDetail', JSON.stringify(listDetail));
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
	// $.ajax({
	// 	url: BASE_URL+'proyek/get-edit/'+id.toLowerCase(),
	// 	type: 'post',
	// 	dataType: 'json',
	// 	data: {'token_edit': $('#token_form').val().trim()},
	// 	beforeSend: function(){},
	// 	success: function(output){
	// 		console.log(output);

	// 		// each dataDetail
	// 		$.each(output.dataDetail, function(i, data){
	// 			var index = indexDetail++;
	// 			var dataDetail = {
	// 				index: index,
	// 				id: data.id,
	// 				id_proyek: data.id_proyek,
	// 				angsuran: data.angsuran,
	// 				persentase: data.persentase,
	// 				total_detail: data.total_detail,
	// 				status_detail: data.status_detail,
	// 				aksi: 'edit',
	// 				delete: false,
	// 			}

	// 			listDetail.push(dataDetail);

	// 			var status = function(value){
	// 				return (data.status_detail.toLowerCase() == value.toLowerCase()) ? 'selected' : ''
	// 			};
	// 			$('#detail_proyekTable > tbody:last-child').append(
	// 				'<tr>'+
	// 					'<td></td>'+ // no
	// 					'<td>' // angsuran
	// 						+'<input type="text" class="form-control input-sm" value="'+data.angsuran+'" '+
	// 						'onchange="onChange_angsuran('+dataDetail.index+', this)"></td>'+
	// 					'<td>'+
	// 						'<div class="input-group">'+
	// 							'<input type="number" min="0" max="100" step="any" onchange="onChange_persentase('+dataDetail.index+',this)" '+
	// 							'class="form-control input-sm" value="'+data.persentase+'">'+
	// 							'<span class="input-group-addon">%</span>'+
	// 						'</div></td>'+ // persentase
	// 					'<td>'+
	// 						'<div class="input-group">'+
	// 							'<span class="input-group-addon">Rp</span>'+
	// 							'<input type="number" min="0" step="any" onchange="onChange_total('+dataDetail.index+',this)" '+
	// 							'class="form-control input-sm" value="'+data.total_detail+'">'+
	// 						'</div></td>'+ // total
	// 					'<td>'+
	// 						'<select onchange="onChange_status('+dataDetail.index+', this)" class="form-control input-sm">'+
	// 							'<option '+status('BELUM DIBAYAR')+'>BELUM DIBAYAR</option>'+
	// 							'<option '+status('LUNAS')+'>LUNAS</option>'+
	// 						'</select>'+
	// 					'</td>'+ // status
	// 					'<td>'+btnAksi_detail(dataDetail.index)+'</td>'+ // aksi
	// 				'</tr>'
	// 			);
	// 			numbering_listDetail();
	// 		});	

	// 		// each dataSkc
	// 		$.each(output.dataSkc, function(i, data){
	// 			var index = indexSkc++;
	// 			var dataSkc = {
	// 				index: index,
	// 				id: data.id,
	// 				id_proyek: data.id_proyek, 
	// 				id_skc: data.id_skc,
	// 				nama: data.nama,
	// 				aksi: 'edit',
	// 				delete: false, 
	// 			}

	// 			listSkc.push(dataSkc);

	// 			var status = function(value){
	// 				return (data.status_detail.toLowerCase() == value.toLowerCase()) ? 'selected' : ''
	// 			};
	// 			$('#sub_kas_kecilTable > tbody:last-child').append(
	// 				'<tr>'+
	// 					'<td></td>'+ // no
	// 					'<td>'+data.id_skc+'</td>'+
	// 					'<td>'+data.nama+'</td>'+
	// 					'<td>'+btnAksi_skc(dataSkc.index)+'</td>'+ // aksi
	// 				'</tr>'
	// 			);
	// 			numbering_listSkc();
	// 		});
	// 	},
	// 	error: function (jqXHR, textStatus, errorThrown){ // error handling
 //            console.log(jqXHR, textStatus, errorThrown);
 //            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
 //        }
	// })
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