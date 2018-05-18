function show_next(step,answer)
{

	nextstep = step + 1;

    if (nextstep==6)
      $('wantbonus').style.display = 'none';

	var r = new Request.HTML({
        
        url: '/my/ajax',
        
        onRequest: function(){
        },

        onSuccess: function(txt, elements, html, js){  
          if (answer == 0)
            $('wrong' + step).style.display = 'block';
          
          if (answer == 1)
            {  
                $('wrong' + step).style.display = 'none';
                $('step' + step).style.display = 'none';
                $('step' + nextstep).style.display = 'block';
                var json = JSON.decode(html);
                json.each(function(item,index){
                $('letter' + nextstep + (index + 1)).set('html',item);
                });

            }
        }
    
    });
    
    r.get({'step': step, 'action': 'get_promo_code'});

}