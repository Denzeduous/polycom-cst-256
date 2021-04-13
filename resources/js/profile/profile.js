document.getElementById('profile-edit'         ).onclick = to_edit;
document.getElementById('add-experience'       ).onclick = add_experience;
document.getElementById('add-experience-cancel').onclick = add_experience_cancel;

[...document.getElementsByName('job-exp-edit')].forEach (function (curr) {
	curr.onclick = function () {job_to_edit (curr.id.split ('-') [0])};
});

[...document.getElementsByName('job-exp-delete')].forEach (function (curr) {
	curr.onclick = function () {job_delete (curr.id.split ('-') [0])};
});

function job_to_edit (id) {
	document.getElementById(id + '-title'     ).style.display = 'none';
	document.getElementById(id + '-title-edit').style.display = 'initial';
	
	if (document.getElementById(id + '-company-edit') !== null) {
		document.getElementById(id + '-company'     ).style.display = 'none';
		document.getElementById(id + '-company-edit').style.display = 'initial';
	}

	document.getElementById(id + '-responsibilities'     ).style.display = 'none';
	document.getElementById(id + '-responsibilities-edit').style.display = 'initial';
	
	document.getElementById(id + '-projects'     ).style.display = 'none';
	document.getElementById(id + '-projects-edit').style.display = 'initial';
	
	document.getElementById(id + '-edit').textContent = 'Save';
	document.getElementById(id + '-edit').onclick     = function () {job_save (id)};
	
	document.getElementById(id + '-delete').textContent = 'Cancel';
	document.getElementById(id + '-delete').onclick     = function () {job_cancel (id)};
}

function job_cancel (id) {
	document.getElementById(id + '-title'     ).style.display = 'initial';
	document.getElementById(id + '-title-edit').style.display = 'none';
	
	if (document.getElementById(id + '-company-edit') !== null) {
		document.getElementById(id + '-company'     ).style.display = 'initial';
		document.getElementById(id + '-company-edit').style.display = 'none';
	}
	
	document.getElementById(id + '-responsibilities'     ).style.display = 'initial';
	document.getElementById(id + '-responsibilities-edit').style.display = 'none';
	
	document.getElementById(id + '-projects'     ).style.display = 'initial';
	document.getElementById(id + '-projects-edit').style.display = 'none';
	
	document.getElementById(id + '-edit').textContent = 'Edit';
	document.getElementById(id + '-edit').onclick     = function () {job_to_edit (id)};
	
	document.getElementById(id + '-delete').textContent = 'Delete';
	document.getElementById(id + '-delete').onclick     = function () {job_delete (id)};
}

function job_save (id) {
	var form = document.getElementById(id + '-form');
	
	if (form.checkValidity()) {
		form.submit();
	}
}

function job_delete (id) {
	window.location.href += '/deleteexperience/' + id;
}

function to_edit () {
	document.getElementById('profile-edit').onclick = save;
	document.getElementById('profile-edit').textContent = "Save Changes";

	var editables = document.getElementsByClassName('profile-editable');

	if (editables.length != 0) {
		[...editables].forEach( function (curr) {
			curr.nextElementSibling.style.display = 'block';
			curr.style.display = 'none';
		});
	}
	
	var empty_editables = document.getElementsByClassName('profile-editable-empty');
	
	if (empty_editables.length != 0) {
		[...empty_editables].forEach( function (curr) {
			var input = curr.nextElementSibling
			input.style.display = 'block';

			curr.style.display = 'none';
		});
	}
}

function save () {
	document.getElementById('profile-form').submit();
}

function add_experience () {
	document.getElementById('add-experience-form'  ).style.display = 'initial';
	document.getElementById('add-experience-cancel').style.display = 'inline';
	document.getElementById('add-experience').textContent = 'Save';
	document.getElementById('add-experience').onclick = add_experience_save;
}

function add_experience_cancel () {
	document.getElementById('add-experience-form'  ).style.display = 'none';
	document.getElementById('add-experience-cancel').style.display = 'none';
	document.getElementById('add-experience').textContent = 'Add Experience';
	document.getElementById('add-experience').onclick = add_experience;
}

function add_experience_save () {
	var form = document.getElementById('add-experience-form');
	
	if (document.getElementById('enddate') !== null) {
		var end_date = document.getElementById('enddate')?.value;
		var is_current = document.getElementById('iscurrent')?.checked;
	
		if (!end_date && !is_current) {
			document.getElementById('add-experience-form-error').style.display = 'initial';
			
			return;
		}
	}

	if (form.reportValidity()) {
		form.submit();
	}
}