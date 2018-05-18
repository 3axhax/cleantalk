function check_agree(fname){

	if ($(fname).checked){
		return true;
	}else{
		return false;
	}
}


function checkSubmit(e, page_id)
{
   	if(e && e.keyCode == 13){
		if (page_id == 3){
			login();
		}else{
			reg();
		}
		return false;
	}
}


function check_login(){
	if ( !check_email('login', /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/, 'login_error') ){
		return false;
	}
	if ( !check_password('password', 'password_error') ){
		return false;
	}

	return true;
}


function check_password(fname, warn){
	var min_size = 5;
	str = $(fname).value;

	if ( str.length >= min_size ){
		$(warn).innerHTML= "";
		$(warn).style.visibility = 'hidden';
		return true;
	}else{
		$(warn).innerHTML= "Минимальная длинна пароля " + min_size + " символов!";
		$(warn).style.visibility = 'visible';
		$(warn).style.color = 'red';
		$(warn).focus();
		return false;
	}
}

function check_reset(){
	if ( $('email').value != '' && !check_email('email', /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/, 'email_error') ){
		alert($('email').value);
		return false;
	}
	return true;
}

function confirm_transfer(message){
	if (confirm(message)){
		window.location = '?transfer_balance=1';
		return true;
	}else{
		return false;
	}

}

function switch_key(service_id){
      
    var auth_key_label = 'auth_key';
    var auth_key_label_h = 'auth_key_h';
    if (service_id) {
        auth_key_label = auth_key_label + '_' + service_id;
        auth_key_label_h = auth_key_label_h + '_' + service_id;
    }

	var key = $(auth_key_label).get('text');
	var key_h = $(auth_key_label_h).value;
	var key_len = key_h.length;
	var asterisks = '';

	if (key === key_h || key == ''){
		for (var i = 0; i < key_len; i++){
			asterisks = asterisks + '*';	
		}
		$(auth_key_label).set('text', asterisks);
	}else{
		$(auth_key_label).set('text', key_h);
	}

	return false;
}

/*
   
*/
function on_account_deletion(){
    $('account_delete_submit').disabled = false;

    if (show_free_offer == 1 && $('password').value != '' && $('free_account_offer').style.display == '') {
        $('free_account_offer').style.display = 'block'; 
        var slide = new Fx.Slide('free_account_offer', {duration: 200}); 
        slide.toggle(); 
    }
    return true;
}

/*
    Switch element visibility.
*/
function show_hide_div(div, div_status)
{
    $(div).style.visibility = div_status;
    return null;
}

/*
    Tests access to an addon.
*/
function check_access_addon(addon_name, output_div, control_element, addon_data) {
    var addon_active = false;

    if (addon_data.enabled) {
        addon_active = true;
    };
    if (!addon_data.enabled) {
        $(output_div).style.display = 'block';
        $(output_div).set('html', addon_data.notice)
        // Allow use the addon on trial.
        if (!addon_data.trial) {
            setTimeout(function(){$(control_element).checked = false;}, 50);
        }
    }
    return addon_active;
}

