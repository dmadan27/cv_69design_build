$(document).ready(function () {
	init();
	setStatusDetail();
	setSkk();
	setBank();

	if($('#submit_proyek').val() == 'action-add') { generateID(); }
	else if($('#submit_proyek').val() == 'action-edit') { getEdit($('#id').val().trim()); }
 	
 	// event on change field
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

    // event on change tgl
    $('#tgl').on('change', function(){
    	if($('#submit_proyek').val() == 'action-add'){ generateID(this.value.split('-')[0]); }
    });

    // event on click button tambah detail
    $('#tambah_detail').on('click', function(){
		console.log('%cButton Tambah Detail Proyek Clicked...', 'font-style: italic');

    	resetModal();
    	$('#modalDetail').modal();
    });

    // event on click button tambah skk
	$('#tambah_skk').on('click', function(){
		console.log('%cButton Tambah Skk Proyek Clicked...', 'font-style: italic');

		if($('#skk').val() != null) { addSkk(); }
    });    

	// event on submit form detail proyek
    $('#form_detail').submit(function(e){
		console.log('%cSubmit Detail Proyek Clicked...', 'font-style: italic');

    	e.preventDefault();
    	addDetail();

    	return false;
    });

    // event on submit form Proyek
    $('#form_proyek').submit(function(e){
		console.log('%cSubmit Proyek Clicked...', 'font-style: italic');

    	e.preventDefault();
    	submit();

    	return false;
    });

    // event on click button reset
    $('#btn_reset').on('click', function(){
		console.log('%cButton Reset Proyek Clicked...', 'font-style: italic');

    	reset();
    });
    
});

/**
 * Function init
 * Proses inisialisasi saat onload page
 */
function init(){
	$('#id').prop('disabled', true);

	//Initialize Select2 Elements
	$('#skk').select2({
    	placeholder: "Pilih Sub Kas Kecil",
		allowClear: true
	});
	
	$('#id_bank').select2({
    	placeholder: "Pilih Bank",
		allowClear: true
    });
	
	// Date picker
    $('.datepicker').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation:"bottom auto",
		todayBtn: true,
  });

  // slider progress
  $('.slider').slider();

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
}

// ===================== Function skk ======================== //

	/**
	 * Function addSkk
	 * Proses penambahan data Skk proyek
	 */
	function addSkk(){
		var index = indexSkk++;

		var data = {
			index: index,
			id: '',
			id_proyek: $('#id').val().trim(), 
			id_skk: $('#skk').val().trim(),
			nama: $('#skk option:selected').text().split(' - ')[1],
			aksi: 'tambah',
			delete: false, 
		};

		if(validSkk(data.id_skk)){
			indexSkk -= 1;
			console.log('%cResponse validSkk Proyek: ', 'font-style: italic; color: blue;', validSkk(data.id_skk));
		}
		else{
			console.log('%cResponse validSkk Proyek: ', 'font-style: italic; color: blue;', validSkk(data.id_skk));
			listSkk.push(data);

			// insert tabel
			renderTableSkk(data);
			$('#skk').val(null).trigger('change');
		}
		
		console.log('%cResponse addSkk Proyek: ', 'font-style: italic; color: blue;', {
			'data': data,
			'index': index,
			'indexSkk': indexSkk
		});
	}

	/**
	 * Function validSkk
	 * Proses pengecekan data skk existing di tabel skk proyek atau tidak
	 * @param {object} skk 
	 * @return {bool} ada
	 */
	function validSkk(skk){
		var ada = false;

		$.each(listSkk, function(index, item){
			if(skk == item.id_skk && !item.delete) { ada = true; }
		});

		return ada;
	}

	/**
	 * Function generateTableSkk
	 * Proses render tbody tabel skk
	 * @param {object} data 
	 */
	function renderTableSkk(data){
		$('#sub_kas_kecilTable > tbody:last-child').append(
			'<tr>'+
				'<td></td>'+ // no
				'<td>'+data.id_skk+'</td>'+
				'<td>'+data.nama+'</td>'+
				'<td>'+btnAksi_skk(data.index)+'</td>'+ // aksi
			'</tr>'
		);
		numbering_listSkk();
	}

	/**
	 * Function numbering_listSkk
	 * Proses generate numbering untuk list table skk
	 */
	function numbering_listSkk(){
		$('#sub_kas_kecilTable tbody tr').each(function(index){
			$(this).children('td:eq(0)').html(index+1);	
		});
	}

	/**
	 * Function btnAksi_skk
	 * Proses render button aksi di table skk proyek
	 * @param {string} index
	 * @return {string} btn
	 */
	function btnAksi_skk(index){
		var btn = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
					' title="Hapus data dari list" onclick="delete_skk('+index+', this)">'+
					'<i class="fa fa-trash"></i></button>';

		return btn;
	}

	/**
	 * Function delete_skk
	 * Proses penghapusan data skk di table skk proyek
	 * @param {string} index 
	 * @param {object} val 
	 */
	function delete_skk(index, val){
		console.log('%cButton Hapus Skk Proyek Clicked...', 'font-style: italic');

		$(val).parent().parent().remove();
		$.each(listSkk, function(i, item){
			if(item.index == index && item.aksi == 'tambah'){
				listSkk.splice(index, 1);	
			}
			else if(item.index == index && item.aksi != 'tambah'){ item.delete = true; }
		});
		numbering_listDetail();
		console.log('%cResponse delete_skk Proyek: ', 'font-style: italic; color: blue;', listSkk);
	}

// =========================================================== //

// ================ Function detail pembayaran proyek =================== //

	/**
	 * Function addDetail
	 * Proses penambahan data detail pemabayran proyek
	 */
	function addDetail(){
		var index = indexDetail++;

		var total_detail = ($('#total_detail').inputmask) ? 
			( parseFloat($('#total_detail').inputmask('unmaskedvalue')) ?
				parseFloat($('#total_detail').inputmask('unmaskedvalue')) : 
				$('#total_detail').inputmask('unmaskedvalue')
			) : $('#total_detail').val().trim();

		var data = {
			index: index,
			id: '',
			id_proyek: $('#id').val().trim(),
			tgl_detail: $('#tgl_detail').val().trim(),
			nama_detail: $('#nama_detail').val().trim(),
			id_bank: $('#id_bank').val(),
			nama_bank: $('#id_bank option:selected').text(),
			total_detail: total_detail,
			aksi: 'tambah',
			delete: false,
		};

		validDetail(data);

		console.log('%cResponse addDetail', 'color: blue', {
			'index': index,
			'indexDetail': indexDetail
		});
	}

	/**
	 * Function validDetail
	 * Proses validasi data inputan detail pembayaran
	 * @param {*} data 
	 */
	function validDetail(data){
		$.ajax({
			url: BASE_URL+'proyek/action-add-detail/',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function(){

			},
			success: function(response){
				console.log('Response validDetail Proyek: ', response);
				if(response.status){
					// tambah data ke list
					listDetail.push(data);

					// tambah data ke tabel
					renderTableDetailPembayaran(data);
					console.log(listDetail);

					$("#modalDetail").modal('hide');
					resetModal();
				}
				else{
					// decrement index utama
					indexDetail -= 1;
					setError(response.error);
				}	
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log('Response Error validDetail Proyek: ', jqXHR, textStatus, errorThrown);
	        }
		})
	}

	/**
	 * Function renderTableDetailPembayaran
	 * Proses render tbody tabel detail pembayaran
	 * @param {object} data 
	 */
	function renderTableDetailPembayaran(data){
		$('#detail_proyekTable > tbody:last-child').append(
			'<tr>'+
				'<td></td>'+ // no
				'<td>'+data.nama_detail+'</td>'+ // nama detail pembayaran
				'<td>'+data.nama_bank+'</td>'+ // bank
				'<td class="text-right">Rp '+data.total_detail+'</td>'+ // total pembayaran
				'<td>'+btnAksi_detail(data.index)+'</td>'+ // aksi
			'</tr>'
		);
		numbering_listDetail();
	}

	/**
	*
	*/
	function numbering_listDetail(){
		$('#detail_proyekTable tbody tr').each(function(index){
			$(this).children('td:eq(0)').html(index+1);	
		});
	}

	/**
	*
	*/
	function btnAksi_detail(index){
		var btn = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
					' title="Hapus data dari list" onclick="delete_detail('+index+', this)">'+
					'<i class="fa fa-trash"></i></button>';

		return btn;
	}

	/**
	*
	*/
	function delete_detail(index, val){
		$(val).parent().parent().remove();
		$.each(listDetail, function(i, item){
			if(item.index == index && item.aksi == 'tambah') {
				// item.delete = true;
				listDetail.splice(index, 1);
			}
			else if(item.index == index && item.aksi != 'tambah') { item.delete = true; }
		});
		numbering_listDetail();
		console.log(listDetail);
	}

// =============== End Function detail pemabyaran proyek ================= //


/**
 * Function getDataForm
 * Proses mendapatkan semua value di field
 * @return {FormData} data
 */
function getDataForm(){
	var data = new FormData();
	
	var luas_area = parseFloat($('#luas_area').val().trim()) ? 
		parseFloat($('#luas_area').val().trim()) : $('#luas_area').val().trim();
	var estimasi = parseFloat($('#estimasi').val().trim()) ? 
		parseFloat($('#estimasi').val().trim()) : $('#estimasi').val().trim();

	var total = ($('#total').inputmask) ? 
		( parseFloat($('#total').inputmask('unmaskedvalue')) ?
			parseFloat($('#total').inputmask('unmaskedvalue')) : 
			$('#total').inputmask('unmaskedvalue')
		) : $('#total').val().trim();

	var dp = ($('#dp').inputmask) ? 
		( parseFloat($('#dp').inputmask('unmaskedvalue')) ?
			parseFloat($('#dp').inputmask('unmaskedvalue')) : 
			$('#dp').inputmask('unmaskedvalue')
		) : $('#dp').val().trim();

	var cco = ($('#cco').inputmask) ? 
		( parseFloat($('#cco').inputmask('unmaskedvalue')) ?
			parseFloat($('#cco').inputmask('unmaskedvalue')) : 
			$('#cco').inputmask('unmaskedvalue')
		) : $('#cco').val().trim();

	var dataProyek = {
		id: $('#id').val().trim(),
		pemilik: $('#pemilik').val().trim(),
		tgl: $('#tgl').val().trim(),
		pembangunan: $('#pembangunan').val().trim(),
		luas_area: $('#luas_area').val().trim(),
		alamat: $('#alamat').val().trim(),
		kota: $('#kota').val().trim(),
		estimasi: $('#estimasi').val().trim(),
		total: total,
		dp: dp,
		cco: cco,
		status: $('#status').val().trim(),
		progress: $('#progress').val(),
	}

	// data.append('token', $('#token').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('dataProyek', JSON.stringify(dataProyek));
	data.append('dataDetail', JSON.stringify(listDetail));
	data.append('dataSkk', JSON.stringify(listSkk));
	data.append('action', $('#submit_proyek').val().trim());

	return data;
}

/**
 * Function submit
 * Proses submit data ke server baik saat add / edit
 * @return {object} response
 */
function submit(){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'proyek/'+$('#submit_proyek').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache :false,
		processData: false,
		beforeSend: function(){
			$('#submit_proyek').prop('disabled', true);
			$('#submit_proyek').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response){
			console.log('%cResponse submit Proyek: ', 'font-weight: bold; color: green;', response);

			if(!response.cek.data_detail) { setNotif(response.notif.data_detail); }

			if(!response.cek.data_skk) { setNotif(response.notif.data_skk); }			

			if(!response.status){
				$('#submit_proyek').prop('disabled', false);
				$('#submit_proyek').html($('#submit_proyek').text());
				setError(response.error);
				setNotif(response.notif.default);
			}
			else { window.location.href = BASE_URL+'proyek/'; }
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('%cResponse Error submit Proyek: ', 'font-weight: bold; color: red;', jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#submit_proyek').prop('disabled', false);
			$('#submit_proyek').html($('#submit_proyek').text());
		}

	})
}

/**
 * Function getEdit
 * Proses request data detail proyek, dan skk untuk proses edit
 * @param {string} id
 * @return {object} response
 */
function getEdit(id){
	$.ajax({
		url: BASE_URL+'proyek/get-edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){},
		success: function(response){
			console.log('%cResponse getEdit Proyek: ', 'font-weight: bold; color: green;', response);

			// each dataDetail
			$.each(response.dataDetail, function(i, data){
				var index = indexDetail++;
				var dataDetail = {
					index: index,
					id: data.id,
					id_proyek: data.id_proyek,
					angsuran: data.angsuran,
					persentase: data.persentase,
					total_detail: data.total_detail,
					status_detail: data.status_detail,
					aksi: 'edit',
					delete: false,
				}

				listDetail.push(dataDetail);

				var status = function(value){
					return (data.status_detail.toLowerCase() == value.toLowerCase()) ? 'selected' : ''
				};
				$('#detail_proyekTable > tbody:last-child').append(
					'<tr>'+
						'<td></td>'+ // no
						'<td>' // angsuran
							+'<input type="text" class="form-control input-sm" value="'+data.angsuran+'" '+
							'onchange="onChange_angsuran('+dataDetail.index+', this)"></td>'+
						'<td>'+
							'<div class="input-group">'+
								'<input type="number" min="0" max="100" step="any" onchange="onChange_persentase('+dataDetail.index+',this)" '+
								'class="form-control input-sm" value="'+data.persentase+'">'+
								'<span class="input-group-addon">%</span>'+
							'</div></td>'+ // persentase
						'<td>'+
							'<div class="input-group">'+
								'<span class="input-group-addon">Rp</span>'+
								'<input type="number" min="0" step="any" onchange="onChange_total('+dataDetail.index+',this)" '+
								'class="form-control input-sm" value="'+data.total_detail+'">'+
							'</div></td>'+ // total
						'<td>'+
							'<select onchange="onChange_status('+dataDetail.index+', this)" class="form-control input-sm">'+
								'<option '+status('BELUM DIBAYAR')+'>BELUM DIBAYAR</option>'+
								'<option '+status('LUNAS')+'>LUNAS</option>'+
							'</select>'+
						'</td>'+ // status
						'<td>'+btnAksi_detail(dataDetail.index)+'</td>'+ // aksi
					'</tr>'
				);
				numbering_listDetail();
			});	

			// each dataSkk
			$.each(response.dataSkk, function(i, data){
				var index = indexSkk++;
				var dataSkk = {
					index: index,
					id: data.id,
					id_proyek: data.id_proyek, 
					id_skk: data.id_skk,
					nama: data.nama,
					aksi: 'edit',
					delete: false, 
				}

				listSkk.push(dataSkk);

				var status = function(value){
					return (data.status_detail.toLowerCase() == value.toLowerCase()) ? 'selected' : ''
				};
				$('#sub_kas_kecilTable > tbody:last-child').append(
					'<tr>'+
						'<td></td>'+ // no
						'<td>'+data.id_skk+'</td>'+
						'<td>'+data.nama+'</td>'+
						'<td>'+btnAksi_skk(dataSkk.index)+'</td>'+ // aksi
					'</tr>'
				);
				numbering_listSkk();
			});
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log('%cResponse Error getEdit Proyek: ', 'font-weight: bold; color: red;', jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
 * Function setError
 * Proses menampilkan pesan error di field-field yang terdapat kesalahan 
 * @param {object} error 
 */
function setError(error){
	$.each(error, function(index, item){
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
 * Function setStatusDetail
 * Proses pengisian select status detail
 */
function setStatusDetail(){
	var status = [
		{value: "BELUM DIBAYAR", text: "BELUM DIBAYAR"},
		{value: "LUNAS", text: "LUNAS"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status_detail").append(option);
	});
}

/**
 * Function setSkk
 * Proses request data Skk untuk keperluan select
 * @return {object} response
 */
function setSkk(){
	$.ajax({
		url: BASE_URL+'proyek/get-skk',
		type: 'post',
		dataType: 'json',
		beforeSend: function(){},
		success: function(response){
			console.log('Response setSkk Proyek: ', response);
			$.each(response, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#skk').append(newOption).trigger('change');
			});
			$('#skk').val(null).trigger('change');
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log('Response Error setSkk Proyek: ', jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
 * Function setBank
 * Proses request data Bank untuk keperluan select
 * @return {object} response
 */
function setBank(){
	$.ajax({
		url: BASE_URL+'proyek/get-bank',
		type: 'post',
		dataType: 'json',
		beforeSend: function(){},
		success: function(response){
			console.log('Response setBank Proyek: ', response);
			$.each(response, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#id_bank').append(newOption).trigger('change');
			});
			$('#id_bank').val(null).trigger('change');
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log('Response Error setBank Proyek: ', jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
 * Function generateID
 * Proses request ID proyek ke server
 * @param {string} tahun default null
 * @return {object} response
 */
function generateID(tahun = null){
	$.ajax({
		url: BASE_URL+'proyek/generate-id/',
		type: 'post',
		dataType: 'json',
		data: {
			'get_tahun': tahun,
		},
		beforeSend: function(){},
		success: function(response){
			console.log('Response generateID Proyek: ', response);
			$('#id').val(response);
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log('Response Error generateID Bank: ', jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
 * Function resetForm
 * Proses reset form proyek
 */
function resetForm(){
	// trigger reset form
	$('#form_proyek').trigger('reset');
	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}

/**
 * Function resetModal
 * Proses reset form modal - form detail proyek
 */
function resetModal(){
	$('#form_detail').trigger('reset');
	$('#id_bank').val(null).trigger('change');
	// hapus semua pesan
	$('#form_detail .pesan').text('');

	// hapus semua feedback
	$('#form_detail .form-group').removeClass('has-success').removeClass('has-error');
}

/**
 * Function reset
 * Proses reset semua form yang ada di proyek
 */
function reset(){
	resetForm();
	resetModal();
	$('#detail_proyekTable tbody tr').remove();
	$('#sub_kas_kecilTable tbody tr').remove();
	indexDetail = indexSkk = 0;
	listDetail = listSkk = [];
	generateID();
}