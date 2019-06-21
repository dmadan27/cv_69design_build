function getEdit(username) {
	$('#username').val(username).attr('readonly', true);
	$('#modalUser').modal();
}

$(document).ready(function () {

	$('#modalUser').on('hidden.bs.modal', function () {
		resetFormUser();
	});

	$('#form_user').submit(async function (e) {
		e.preventDefault();

		const username = $('#username').val().trim();
		const password = $('#password').val().trim();

		if (validateFormUser(username, password)) {
			const formData = new FormData();
			formData.append('username', username);
			formData.append('password', password);

			$('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');

			await fetch(BASE_URL + 'user/reset-password/', {
				method: 'POST',
				body: formData,
			}).then(async (res) => {
				try {
					const data = await res.clone().json();
					if (data.success) {
						setNotif({
							type: 'success',
							message: data.message,
						});
						$('#modalUser').modal('hide');
					} else {
						swal("Pesan", data.message, "info");
					}
				} catch (error) {
					console.log(error);
					const log = await res.clone().text();
					console.log(log);
					swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
				}
			}).catch((error) => {
				console.log(error);
				swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			});

			$('.box .overlay').remove();
		}
	});
});

function validateFormUser(username, password) {
	cleanErrorUser();
	let valid = true;

	if (username == '') {
		$('.field-username').addClass('has-error');
		$('.pesan-username').text('Username harus diisi.');
		valid = false;
	}

	if (password.length < 5) {
		$('.field-password').addClass('has-error');
		$('.pesan-password').text('Password harus berisi lebih dari 5 karakter.');
		valid = false;
	}

	return valid;
}

function cleanErrorUser() {
	$('.pesan-username').text('');
	$('.field-username').removeClass('has-success').removeClass('has-error');

	$('.pesan-password').text('');
	$('.field-password').removeClass('has-success').removeClass('has-error');
}

function resetFormUser() {
	cleanErrorUser();
	$('#form_user').trigger('reset');
}