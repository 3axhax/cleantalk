$(document).ready(function(){	

    // Langs switch

    $('.menulangs').click(function(){

      paramName = 'lang';
      paramValue = this.id;
    	var url = window.location.href;
      if (url.indexOf(paramName + "=") >= 0) {
        var prefix = url.substring(0, url.indexOf(paramName));
        var suffix = url.substring(url.indexOf(paramName));
        suffix = suffix.substring(suffix.indexOf("=") + 1);
        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
        url = prefix + paramName + "=" + paramValue + suffix;
      }
      else {
        if (url.indexOf("?") < 0)
          url += "?" + paramName + "=" + paramValue;
        else
           url += "&" + paramName + "=" + paramValue;
      }

      window.location.href = url;
      return true;
	  	
	  });

    // Show hoster API key

    $('#hoster_api_key').click(function(){
      $('#hoster_api_key').val($('#hoster_api_key_h').val());
    });

    // Enable Delete account button in delete account page

    $('#notice').keyup(function(){
       $('#delete_button').prop('disabled', false);
    });


});