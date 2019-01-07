$(document).ready(function () {
	init();

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

		// disable checkbox DP
		if(checkDP) { $('#is_DP').prop('disabled', true); }
		else { $('#is_DP').prop('disabled', false); }

		console.log('%cCheck DP: ', 'color: blue; font-style: italic', checkDP);
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
		if($('#submit_detail').val() == "tambah") { addDetail(); }
		else if($('#submit_detail').val() == "edit") { editDetail(); }

    	return false;
    });

	// event on change DP detail proyek
	$('#is_DP').on('change', function() {
		if($(this).is(":checked")) {
			$('#total_detail').val($('#dp').val());
		}
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
	console.log('%cFunction init run...', 'color: blue; font-style: blue');
	
	$('#id').prop('disabled', true);

	// Initialize Select2 Elements
	$('#status').select2({
    	placeholder: "Pilih Status Proyek",
		allowClear: true
	});
	
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
	
	$('#submit_detail').val('tambah');
	$('#submit_detail').text('Tambah Detail Pembayaran');

	setStatus();
	setStatusDetail();
	setSkk();
	setBank();
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
			'indexSkk': indexSkk,
			'listSkk': listSkk
		});
	}

	/**
	 * Function validSkk
	 * Proses pengecekan data skk existing di tabel skk proyek atau tidak
	 * @param {object} skk 
	 * @return {bool} ada false --> validasi sukses, true --> item sudah ada
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
		listSkk[index].delete = true;

		numbering_listSkk();
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

		var is_DP = ($('#is_DP').is(":checked")) ? $('#is_DP').val().trim() : "0";

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
			tgl_detail_full: '',
			nama_detail: $('#nama_detail').val().trim(),
			id_bank: $('#id_bank').val(),
			nama_bank: $('#id_bank option:selected').text(),
			total_detail: total_detail,
			total_detail_full: '',
			is_DP: is_DP,
			aksi: 'tambah',
			delete: false,
		};

		validDetail(data);

		console.log('%cResponse addDetail', 'font-style: italic; color: blue', {
			'index': index,
			'indexDetail': indexDetail,
			'data': data,
			'listDetail': listDetail
		});
	}

	/**
	 * Function validDetail
	 * Proses validasi data inputan detail pembayaran
	 * @param {object} data 
	 * @param {string} action validasi untuk tambah atau edit detail
	 */
	function validDetail(data, action = 'tambah'){
		$.ajax({
			url: BASE_URL+'proyek/action-add-detail/',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function(){
			},
			success: function(response){
				console.log('%cResponse validDetail Proyek: ', 'font-style: italic; color: blue', response);
				if(action == 'tambah') {
					if(response.status){
						// tambah data ke list
						listDetail.push(data);

						// tambah data ke tabel
						renderTableDetailPembayaran(response.data);

						if(data.is_DP === '1') { checkDP = true; }

						$("#modalDetail").modal('hide');
						resetModal();
					}
					else{
						// decrement index utama
						indexDetail -= 1;
						setError(response.error);
					}
				}
				else if(action == 'edit') {
					if(response.status) {
						listDetail[data.index].tgl_detail = data.tgl_detail;
						listDetail[data.index].tgl_detail_full = response.data.tgl_detail_full;
						listDetail[data.index].nama_detail = data.nama_detail;
						listDetail[data.index].id_bank = data.id_bank;
						listDetail[data.index].nama_bank = data.nama_bank;
						listDetail[data.index].total_detail = data.total_detail;
						listDetail[data.index].total_detail_full = response.data.total_detail_full;
						listDetail[data.index].is_DP = data.is_DP;
						listDetail[data.index].aksi = data.aksi;
						
						$('#detail_proyekTable tbody tr').remove();
						$.each(listDetail, function(i, item){
							if(!item.delete) {
								renderTableDetailPembayaran(item);
							}
						});

						$("#modalDetail").modal('hide');
						resetModal();
	
					}
					else {
						setError(response.error);
					}
				}
					
			},
			error: function (jqXHR, textStatus, errorThrown){
	            console.log('%cResponse Error validDetail Proyek: ', 'font-style: italic; color: red', jqXHR, textStatus, errorThrown);
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
				'<td>'+data.tgl_detail_full+'</td>'+ // nama detail pembayaran
				'<td>'+data.nama_detail+'</td>'+ // nama detail pembayaran
				'<td>'+data.nama_bank+'</td>'+ // bank
				'<td class="text-right">'+data.total_detail_full+'</td>'+ // total pembayaran
				'<td>'+btnAksi_detail(data.index)+'</td>'+ // aksi
			'</tr>'
		);
		numbering_listDetail();
	}

	/**
	 * Function numbering_listDetail
	 * Proses generate numbering untuk list table detail pembayaran
	 */
	function numbering_listDetail(){
		$('#detail_proyekTable tbody tr').each(function(index){
			$(this).children('td:eq(0)').html(index+1);	
		});
	}

	/**
	 * Function btnAksi_detail
	 * Proses render button aksi di table detail pembayaran proyek
	 * @param {string} index
	 * @return {string} btn
	 */
	function btnAksi_detail(index){
		var btnEdit = '<button type="button" class="btn btn-success btn-flat btn-sm"'+
					' title="Edit detail" onclick="get_edit_detail('+index+')">'+
					'<i class="fa fa-pencil"></i></button>';
		var btnHapus = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
					' title="Hapus data dari list" onclick="delete_detail('+index+', this)">'+
					'<i class="fa fa-trash"></i></button>';

		var btn = '<div class="btn-group">'+btnEdit+btnHapus+'</div>';
		return btn;
	}

	/**
	 * Fuction edit_detail
	 * Proses get data edit detail di table detail pembayaran proyek
	 * @param {string} index 
	 * @param {object} val 
	 */
	function get_edit_detail(index) {
		console.log('%cButton Edit Detail Pembayaran Proyek Clicked...', 'font-style: italic');

		resetModal();

		// load data ke modal
		var data = listDetail[index];

		var bank = (data.id_bank != "" && data.id_bank != null) ? data.id_bank : false;
		if(bank) { $('#id_bank').val(bank).trigger('change'); }

		$('#index_detail').val(data.index);
		$('#id_detail').val(data.id);
		$('#tgl_detail').val(data.tgl_detail);
		$('#nama_detail').val(data.nama_detail);
		$('#total_detail').val(data.total_detail);

		if(data.is_DP === "1") {
			$('#is_DP').prop('disabled', false);
			$('#is_DP').prop('checked', true);
		}

		$('#submit_detail').val('edit');
		$('#submit_detail').text('Edit Detail Pembayaran');

		$('#modalDetail').modal();

		console.log('%cCheck DP: ', 'color: blue; font-style: italic', checkDP);
	}

	/**
	 * Function editDetail
	 * Proses edit data detail pembayaran proyek
	 */
	function editDetail() {
		var aksi = ($('#submit_proyek').val() == 'action-add') ? 'tambah' : 'edit';
		var is_DP = ($('#is_DP').is(":checked")) ? $('#is_DP').val().trim() : "0";
		var total_detail = ($('#total_detail').inputmask) ? 
			( parseFloat($('#total_detail').inputmask('unmaskedvalue')) ?
				parseFloat($('#total_detail').inputmask('unmaskedvalue')) : 
				$('#total_detail').inputmask('unmaskedvalue')
			) : $('#total_detail').val().trim();
		
		var data = {
			index: $('#index_detail').val().trim(),
			id: $('#id_detail').val().trim(),
			id_proyek: $('#id').val().trim(),
			tgl_detail: $('#tgl_detail').val().trim(),
			tgl_detail_full: '',
			nama_detail: $('#nama_detail').val().trim(),
			id_bank: $('#id_bank').val(),
			nama_bank: $('#id_bank option:selected').text(),
			total_detail: total_detail,
			total_detail_full: '',
			is_DP: is_DP,
			aksi: aksi,
			delete: false,
		};

		validDetail(data, 'edit');

		console.log('%cResponse editDetail', 'font-style: italic; color: blue', {
			'index': data.index,
			'indexDetail': indexDetail,
			'data': data,
			'listDetail': listDetail
		});
	}

	/**
	 * Function delete_detail
	 * Proses penghapusan data detail di table detail pembayaran proyek
	 * @param {string} index 
	 * @param {object} val 
	 */
	function delete_detail(index, val){
		console.log('%cButton Hapus Detail Pembayaran Proyek Clicked...', 'font-style: italic');

		$(val).parent().parent().parent().remove();
		listDetail[index].delete = true;
		if(listDetail[index].is_DP === "1") { checkDP = false }

		numbering_listDetail();

		console.log('%cResponse delete_detail Proyek: ', 'font-style: italic; color: blue;', listDetail);
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

	var status = ($('#status').val() != "" && $('#status').val() != null) ? $('#status').val().trim() : "";

	var dataProyek = {
		id: $('#id').val().trim(),
		pemilik: $('#pemilik').val().trim(),
		tgl: $('#tgl').val().trim(),
		pembangunan: $('#pembangunan').val().trim(),
		luas_area: luas_area,
		alamat: $('#alamat').val().trim(),
		kota: $('#kota').val().trim(),
		estimasi: estimasi,
		total: total,
		dp: dp,
		cco: cco,
		status: status,
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

			$('#submit_proyek').prop('disabled', false);
			$('#submit_proyek').html($('#submit_proyek').text());

			if(!response.cek.data_skk) { setNotif(response.notif.data_skk); }			

			if(!response.status){
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
					tgl_detail: data.tgl_detail,
					tgl_detail_full: data.tgl_detail_full,
					nama_detail: data.nama_detail,
					id_bank: data.id_bank,
					nama_bank: data.nama_bank,
					total_detail: data.total_detail,
					total_detail_full: data.total_detail_full,
					is_DP: data.is_DP,
					aksi: 'edit',
					delete: false,
				}

				listDetail.push(dataDetail);
				renderTableDetailPembayaran(dataDetail);

				if(data.is_DP === '1') { checkDP = true; }
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
				renderTableSkk(dataSkk);
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
 * Function setStatus
 * Proses pengisian select status di form proyek
 */
function setStatus() {
	var status = [
		{value: "BERJALAN", text: "BERJALAN"},
		{value: "SELESAI", text: "SELESAI"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option).trigger('change');
	});
	
	// set value status
	if($('#submit_proyek').val() == "action-edit") { $('#status').val(statusProyek).trigger('change'); }
	else { $('#status').val(null).trigger('change'); }
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
			console.log('%cResponse setSkk Proyek: ', 'color: blue; font-style: italic', response);
			$.each(response, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#skk').append(newOption).trigger('change');
			});
			$('#skk').val(null).trigger('change');
		},
		error: function (jqXHR, textStatus, errorThrown){
            console.log('%cResponse Error setSkk Proyek: ', 'color: red; font-style: italic', jqXHR, textStatus, errorThrown);
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
			console.log('%cResponse setBank Proyek: ', 'color: blue; font-style: italic', response);
			$.each(response, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#id_bank').append(newOption).trigger('change');
			});
			$('#id_bank').val(null).trigger('change');
		},
		error: function (jqXHR, textStatus, errorThrown){
            console.log('%cResponse Error setBank Proyek: ', 'color: red; font-style: italic', jqXHR, textStatus, errorThrown);
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
			console.log('%cResponse generateID Proyek: ', 'color: blue; font-style: italic', response);
			$('#id').val(response);
		},
		error: function (jqXHR, textStatus, errorThrown){
            console.log('%cResponse Error generateID Bank: ', 'color: red; font-style: italic', jqXHR, textStatus, errorThrown);
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

	$('#submit_detail').val('tambah');
	$('#submit_detail').text('Tambah Detail Pembayaran');

	$('#is_DP').prop('checked', false);
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
	checkDP = false;
	generateID();
}