document.getElementById('profile-delete'        ).onclick = delete_user   ;
document.getElementById('profile-delete-cancel' ).onclick = delete_cancel ;
document.getElementById('profile-delete-delete' ).onclick = delete_send   ;

document.getElementById('profile-suspend'        ).onclick = suspend_user  ;
document.getElementById('profile-suspend-cancel' ).onclick = suspend_cancel;
document.getElementById('profile-suspend-suspend').onclick = suspend_send  ;

function delete_user () {
	document.getElementById('profile-popup-background' ).style.display = 'initial';
	document.getElementById('profile-popup-form-delete').style.display = 'initial';
}

function delete_cancel () {
	document.getElementById('profile-popup-background' ).style.display = 'none';
	document.getElementById('profile-popup-form-delete').style.display = 'none';
	document.getElementById('profile-delete-password'  ).value         = ''    ;
}

function delete_send () {
	var form = document.getElementById('profile-popup-form-delete');
	
	if (form.checkValidity()) {
		form.submit();
	}
}

function suspend_user () {
	document.getElementById('profile-popup-background'  ).style.display = 'initial';
	document.getElementById('profile-popup-form-suspend').style.display = 'initial';
}

function suspend_cancel () {
	document.getElementById('profile-popup-background'  ).style.display = 'none';
	document.getElementById('profile-popup-form-suspend').style.display = 'none';
	document.getElementById('profile-suspend-password'  ).value         = ''    ;
}

function suspend_send () {
	var form = document.getElementById('profile-popup-form-suspend');

	if (form.reportValidity()) {
		form.submit();
	}
}