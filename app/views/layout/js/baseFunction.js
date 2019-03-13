/**
 * Method setActiveMenu
 * @param {object} url windows.location.href
 */
function setActiveMenu(url, level) {
    var newUrl = [];
    
    $.each(url, function(index, item) {
        if(item != "localhost" && item != "dev" && item != "test") {
            newUrl.push(item);
        }
    });

    // menu dashboard
    if(newUrl[3] == 'home' || newUrl[3] == '') {
        $('.menu-home').addClass('active');
    }
    // menu timesheet
    else if(newUrl[3] == 'timesheet') {
        $('.menu-timesheet').addClass('active');
    }
    // menu contact
    else if(newUrl[3] == 'contact') {
        $('.menu-contact').addClass('active');
    }
    // menu project
    else if(newUrl[3] == 'project') {
        $('.menu-project').addClass('active');
    }
    // menu category
    else if(newUrl[3] == 'category') {
        $('.menu-category').addClass('active');
    }
    // menu user
    else if(newUrl[3] == 'user') {
        $('.menu-user').addClass('active');
    }
    // menu login history
    else if(newUrl[3] == 'login-history') {
        $('.menu-login-history').addClass('active');
    }
}

/**
 * Function setNotif
 * Base function untuk akses notfikasi toastr
 * @param {object} notif
 * @param {type} string
 */
function setNotif(notif, type = 'toastr') {
    if(type == 'toastr') {
        switch(notif.type){
            case 'success':
                toastr.success(notif.message, notif.title);
                break;
            case 'warning':
                toastr.warning(notif.message, notif.title);
                break;
            case 'error':
                toastr.error(notif.message, notif.title);
                break;
            default:
                toastr.info(notif.message, notif.title);
                break; 
        }
    }
    else if(type == 'swal') {
        swal(notif.message, notif.title, notif.type);
    }
    else {
        alert(notif.title+'\n'+notif.message);
    }
    
}

/**
 * Function onChangeField
 * Base function untuk setiap event onchange semua field yang ada di form
 * @param {object} scope
 */
function onChangeField(scope) {
    if(scope.value !== ""){
        $('.field-'+scope.id).removeClass('has-error').addClass('has-success');
        $(".pesan-"+scope.id).text('');
    }
    else{
        $('.field-'+scope.id).removeClass('has-error').removeClass('has-success');
        $(".pesan-"+scope.id).text('');	
    }
}

/**
 * 
 */
function refreshTable(table, refresh) {
    refresh.prop('disabled', true);
    table.ajax.reload(function(response) {
        refresh.prop('disabled', false);
    }, false);
}

/**
 *
 */
function goBack() {
    window.history.back();
}