document.getElementById('isbusiness').onclick = to_business;

function to_business () {
	document.getElementById('firstname-group').style.display = 'none';
	document.getElementById('firstname'      ).required      = false;
	document.getElementById('lastname-group' ).style.width   = '100%';
	document.getElementById('lastname-label' ).textContent   = 'Company Name';
	document.getElementById('lastname'       ).placeholder   = 'Company Name';
	document.getElementById('lastname'       ).style.width   = '98%';

	document.getElementById('isbusiness').onclick = to_user;
}

function to_user () {
	document.getElementById('firstname-group').style.display = 'inline';
	document.getElementById('firstname'      ).required      = true;
	document.getElementById('lastname-group' ).style.width   = '50%';
	document.getElementById('lastname-label' ).textContent   = 'Last Name';
	document.getElementById('lastname'       ).placeholder   = 'Last Name';
	document.getElementById('lastname'       ).style.width   = '95%';

	document.getElementById('isbusiness').onclick = to_business;
}