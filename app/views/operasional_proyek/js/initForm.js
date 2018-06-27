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

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });

 	
	//tambah detail
    $('#btn_tambahDetail').on('click', function(){
    	// console.log(listDetail);
    	// resetModal();
    	$('#modalDetailOperasionalKaskecil').modal();
    });

    // Submit Proyek
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
 });

// ================ Function detail proyek =================== //

	/**
	*
	*/
	function addDetail(){
		var index = indexDetail++;

		var qty_detail = parseFloat($('#qty_detail').val().trim()) ? parseFloat($('#qty_detail').val().trim()) : $('#qty_detail').val().trim();
		var harga_detail = parseFloat($('#harga_detail').val().trim()) ? parseFloat($('#harga_detail').val().trim()) : $('#harga_detail').val().trim();
		var sub_total_detail = parseFloat($('#sub_total_detail').val().trim()) ? parseFloat($('#sub_total_detail').val().trim()) : $('#sub_total_detail').val().trim();
		var harga_asli_detail = parseFloat($('#harga_asli_detail').val().trim()) ? parseFloat($('#harga_asli_detail').val().trim()) : $('#harga_asli_detail').val().trim();
		var sisa_detail = parseFloat($('#sisa_detail').val().trim()) ? parseFloat($('#sisa_detail').val().trim()) : $('#sisa_detail').val().trim();
		 
		var data = {
			index: index,
			id: '',
			id_operasional_proyek : $('#id_operasional_proyek').val().trim(),
			nama : $('#nama_detail').val().trim(),
			jenis : $('#jenis_detail').val().trim(),
			satuan : $('#satuan_detail').val().trim(),
			qty : qty_detail,
			harga : harga_detail,
			sub_total : sub_total_detail,
			status :  $('#status').val().trim(),
			aksi: 'tambah',
			delete: false,
		};

		validDetail(data);

		console.log('Index : '+index);
		console.log('Index Utama: '+indexDetail);
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

					// '<input type="text" class="form-control" value="'+data.angsuran+'" onchange="onChange_angsuran('+index+', this)">'
					// '<div class="input-group"><input type="number" min="0" step="any" onchange="onChange_persentase('+data.index+',this)" class="form-control input-sm" value="'+data.persentase+'"><span class="input-group-addon">%</span></div>'

					var status = function(value){
						return (data.status_detail.toLowerCase() == value.toLowerCase()) ? 'selected' : ''
					};

					// tambah data ke tabel
					$('#detail_OperasionalProyekTable > tbody:last-child').append(
						'<tr>'+
							'<td></td>'+ // no
							'<td>' // nama
								+'<input type="text" class="form-control input-sm" value="'+data.nama+'" '+
								'onchange="onChange_nama('+data.index+', this)"></td>'+
							'<td>'+
								'<div class="form-group field-nama_detail has-feedback">'+
                                '<label for="nama_detail">Nama</label>'+
                                '<input type="text" class="form-control field" id="nama_detail"'+
                                ' name="nama" placeholder="Masukan Nama Kebutuhan" onchange="onChange_jenis('+data.index+',this)">'+
                                '<span class="help-block small pesan pesan-nama_detail"></span></div></td>'+
							'<td>'+btnAksi_detail(data.index)+'</td>'+ // aksi
						'</tr>'
					);
					numbering_listDetail();
					console.log(listDetail);

					$("#modalDetailOperasionalKaskecil").modal('hide');
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
	        }
		})
	}


	/**
	*
	*/
	function numbering_listDetail(){
		$('#detail_OperasionalProyekTable tbody tr').each(function(index){
			$(this).children('td:eq(0)').html(index+1);	
		});
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
				$('#id_proyek').append(newOption).trigger('change');
			});
			$('#id_proyek').val(null).trigger('change');
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
	function numbering_listDetail(){
		// $('#detail_proyekTable tbody tr').each(function(index){
		// 	$(this).children('td:eq(0)').html(index+1);	
		// });
	}

	/**
	*
	*/
	function btnAksi_detail(index){
		// var btn = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
		// 			' title="Hapus data dari list" onclick="delete_detail('+index+', this)">'+
		// 			'<i class="fa fa-trash"></i></button>';

		// return btn;
	}

	/**
	*
	*/
	function onChange_angsuran(index, val){
		// $.each(listDetail, function(i, item){
		// 	if(item.index == index){
		// 		item.angsuran = val.value;
		// 	}
		// });
		// numbering_listDetail();

		// console.log(listDetail);

	}

	/**
	*
	*/
	function onChange_persentase(index, val){
		// $.each(listDetail, function(i, item){
		// 	if(item.index == index){
		// 		item.persentase = parseFloat(val.value);
		// 	}
		// });
		// numbering_listDetail();

		// console.log(listDetail);
	}

	/**
	*
	*/
	function onChange_total(index, val){
		// $.each(listDetail, function(i, item){
		// 	if(item.index == index){
		// 		item.total_detail = parseFloat(val.value);
		// 	}
		// });
		// numbering_listDetail();

		// console.log(listDetail);
	}

	/**
	*
	*/
	function onChange_status(index, val){
		// $.each(listDetail, function(i, item){
		// 	if(item.index == index){
		// 		item.status_detail = val.value;
		// 	}
		// });
		// numbering_listDetail();

		// console.log(listDetail);
	}

	/**
	*
	*/
	function delete_detail(index, val){
		// $(val).parent().parent().remove();
		// $.each(listDetail, function(i, item){
		// 	if(item.index == index) item.delete = true;
		// });
		// numbering_listDetail();
		// console.log(listDetail);
	}

// =========================================================== //

// ===================== Function skc ======================== //

	/**
	*
	*/
	function addSkc(){
		// var index = indexSkc++;

		// var data = {
		// 	index: index,
		// 	id: '',
		// 	id_proyek: $('#id').val().trim(), 
		// 	id_skc: $('#skc').val().trim(),
		// 	nama: $('#skc option:selected').text().split(' - ')[1],
		// 	aksi: 'tambah',
		// 	delete: false, 
		// };

		// // validDetail(data);
		// console.log(data);

		// if(validSkc(data.id_skc)){
		// 	indexSkc -= 1;
		// 	console.log(validSkc(data.id_skc));
		// }
		// else{
		// 	console.log(validSkc(data.id_skc));
		// 	listSkc.push(data);
		// 	// insert tabel
		// 	$('#sub_kas_kecilTable > tbody:last-child').append(
		// 		'<tr>'+
		// 			'<td></td>'+ // no
		// 			'<td>'+data.id_skc+'</td>'+
		// 			'<td>'+data.nama+'</td>'+
		// 			'<td>'+btnAksi_skc(data.index)+'</td>'+ // aksi
		// 		'</tr>'
		// 	);

		// 	$('#skc').val(null).trigger('change');
		// 	numbering_listSkc();
		// }
		
		// console.log('Index : '+index);
		// console.log('Index Utama: '+indexSkc);
	}
	
	/**
	*
	*/
	function validSkc(skc){
		// var ada = false;

		// $.each(listSkc, function(index, item){
		// 	if(skc == item.id_skc && !item.delete) ada = true;
		// });

		// return ada;
	}

	/**
	*
	*/
	function numbering_listSkc(){
		// $('#sub_kas_kecilTable tbody tr').each(function(index){
		// 	$(this).children('td:eq(0)').html(index+1);	
		// });
	}

	/**
	*
	*/
	function btnAksi_skc(index){
		// var btn = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
		// 			' title="Hapus data dari list" onclick="delete_skc('+index+', this)">'+
		// 			'<i class="fa fa-trash"></i></button>';

		// return btn;
	}

	/**
	*
	*/
	function delete_skc(index, val){
		// $(val).parent().parent().remove();
		// $.each(listSkc, function(i, item){
		// 	if(item.index == index && item.aksi == 'edit') item.status = true;
		// 	// else (item.index == index && item.aksi == 'tambah'){
				
		// 	// }
		// });
		// numbering_listDetail();
		// console.log(listSkc);
	}

// =========================================================== //

/**
*
*/
function getDataForm(){
	var data = new FormData();
	
	var total = parseFloat($('#total').val().trim()) ? parseFloat($('#total').val().trim()) : $('#total').val().trim();
	// var estimasi= parseFloat($('#estimasi').val().trim()) ? parseFloat($('#estimasi').val().trim()) : $('#estimasi').val().trim();
	// var total= parseFloat($('#total').val().trim()) ? parseFloat($('#total').val().trim()) : $('#total').val().trim();
	// var dp= parseFloat($('#dp').val().trim()) ? parseFloat($('#dp').val().trim()) : $('#dp').val().trim();
	// var cco = parseFloat($('#cco').val().trim()) ? parseFloat($('#cco').val().trim()) : $('#cco').val().trim();

	// var dataProyek = {
	// 	id: $('#id').val().trim(),
	// 	id_proyek: $('#id_proyek').val().trim(),
	// 	tgl: $('#tgl').val().trim(),
	// 	nama: $('#nama').val().trim(),
	// 	total: $('#total').val().trim(),
	// }

	data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('id_proyek', $('#id_proyek').val().trim());
	data.append('tgl', $('#tgl').val().trim());
	data.append('nama', $('#nama').val().trim());
	data.append('total', $total);


	// data.append('dataProyek', JSON.stringify(dataProyek));
	// data.append('dataDetail', JSON.stringify(listDetail));
	// data.append('dataSkc', JSON.stringify(listSkc));
	data.append('action', $('#submit_operasional_proyek').val().trim());

	return data;
}

/**
*
*/
function submit(){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'operasional_proyek/'+$('#submit_operasional_proyek').val().trim()+'/',
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
				toastr.warning(output.notif.message, output.notif.title);
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
	// $.each(error, function(index, item){
	// 	console.log(index);

	// 	if(item != ""){
	// 		$('.field-'+index).removeClass('has-success').addClass('has-error');
	// 		$('.pesan-'+index).text(item);
	// 	}
	// 	else{
	// 		$('.field-'+index).removeClass('has-error').addClass('has-success');
	// 		$('.pesan-'+index).text('');	
	// 	}
	// });
}

/**
*
*/
function setStatusDetail(){
	// var status = [
	// 	{value: "BELUM DIBAYAR", text: "BELUM DIBAYAR"},
	// 	{value: "LUNAS", text: "LUNAS"},
	// ];

	// $.each(status, function(index, item){
	// 	var option = new Option(item.text, item.value);
	// 	$("#status_detail").append(option);
	// });
}

/**
*
*/
function setSkc(){
	// $.ajax({
	// 	url: BASE_URL+'proyek/get-skc',
	// 	dataType: 'json',
	// 	beforeSend: function(){},
	// 	success: function(data){
	// 		console.log(data);
	// 		$.each(data, function(index, item){
	// 			var newOption = new Option(item.text, item.id);
	// 			$('#skc').append(newOption).trigger('change');
	// 		});
	// 		$('#skc').val(null).trigger('change');
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
function setStatusDetailOperasionalProyek(){
		$.ajax({
		url: BASE_URL+'operasional-proyek/get-status-detail-operasional-proyek',
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			console.log(data);
			$.each(data, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#status').append(newOption).trigger('change');
			});
			$('#status').val(null).trigger('change');
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
function generateID(){
	$.ajax({
		url: BASE_URL+'operasional-proyek/get-last-id/',
		type: 'post',
		data: {token: $('#token_form').val().trim()},
		beforeSend: function(){},
		success: function(output){
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
function resetForm(){
	// trigger reset form
	$('#form_operasional_proyek').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}

/**
*
*/
function resetModal(){
	$('#form_detail').trigger('reset');
	$('modalDetailOperasionalKaskecil').trigger('reset');

	// hapus semua pesan
	$('#form_detail .pesan').text('');

	// hapus semua feedback
	$('#form_detail .form-group').removeClass('has-success').removeClass('has-error');
}