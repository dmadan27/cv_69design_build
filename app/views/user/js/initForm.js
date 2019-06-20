const FormUser = {
	'show': ({username, level}) => {
		$('#username').val(username).attr('readonly', true);
		$('#level').val(level);

		if ((level == 'OWNER') || (level == "SUB KAS KECIL")) {
			$('#level').attr('disabled', true);
		} else {
			$('#level').attr('disabled', false);
		}

		$('#modalUser').modal();
	}
}