$(document).ready(function(){	

  $('#show_new_ticket').click(function(){  
		  
	  if ($('.new_ticket_form').is(':visible')) 
	  
	    $('.new_ticket_form').hide();

	  else
	    {
          $('.new_ticket_form').show(); 

          $('#show_new_ticket').hide();          	    

          $('#t_subject').focus();            
        }
      return false;
	  	
	});

  $('#t_button').click(function(){

      $('#t_button').attr('disabled',true);

      $('#t_form').submit();
         
  }); 

  $(document).on('keyup', "#t_subject", function() { 
    if ($('#t_subject').val().length > 3) {
      $.ajax({ 
        url: '/my/support?action=search_phrase',
        type: "POST",
        data: ({ search_phrase : $('#t_subject').val(),}),
        beforeSend: function(){},
        success: function(response) {
          if (response != '') {  
            $('#searchphrase').show();
            $('#searchphrase').html(response);
          }      
        }

      });    
    }
    else{
      $('#searchphrase').hide();
      $('#searchphrase').html('');  
    }  
  });
    

});