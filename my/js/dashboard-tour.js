window.addEventListener('load', function(){
  var open_popover = 0;
  $('.tour').click(function(){
    $('.mpopover:first').trigger('click');
  });
  
  $(document).on('click','.popover-title .close', function(){
    var id = $(this).data('id');
    $('.mpopover[data-id="'+id+'"]').trigger('click');
  });
  $(document).on('click','.popover-nav', function(e){
    e.preventDefault();
    var navid = $(this).data('navid');
    var navurl = $(this).data('navurl');
    if(!$('.mpopover[data-id="'+navid+'"]').length){
      location.href = navurl+'#show='+navid;
      return false;
    }
    $('.mpopover[data-id="'+navid+'"]').trigger('click');
  });
  $('.mpopover').on('show.bs.popover', function () {
    open_popover = $(this).data('id');
    $(this).parents('th').css('background-color','#ccc');
    $('.mpopover').not(this).each(function(item){
      if ($(this).next('div.popover:visible').length){
        $(this).trigger('click');
      }
    });
  }).on('hidden.bs.popover', function () {
    //open_popover = 0;
    $(this).parents('th').css('background-color','#f5f5f5');
  });
  $('.mpopover').click(function(e){
    e.preventDefault();
    var btn = $(this);
    var id = $(this).data('id');    
    if(btn.data('loaded')!=1)
    $.ajax({
      method: "POST",
      dataType: "json",
      url: "/my/ajax?action=get_tour_widget",
      data: { id: id }
    })
      .done(function( data ) {
        btn.removeClass('disabled');
        if(data.error){
          console.log( data.error );
        }else{
          if(data.article.prev){
            var prev = '<a href="#" class="btn btn-default pull-left popover-nav" data-navid="'+data.article.prev+'" data-navurl="'+data.article.prev_url+'">'+l_btn_prev+'</a>';
          }else{
            var prev = '<a href="#" class="btn btn-default pull-left disabled">'+l_btn_prev+'</a>';
          }
          if(data.article.next){
            var next = '<a href="#" class="btn btn-default pull-right popover-nav" data-navid="'+data.article.next+'" data-navurl="'+data.article.next_url+'">'+l_btn_next+'</a>';
          }else{
            var next = '<a href="#" class="btn btn-default pull-right disabled">'+l_btn_next+'</a>';
          }
          if(data.article.seo_url){
            var more = '<a href="'+data.article.seo_url+'" class="btn btn-primary" target="_blank">'+l_learn_more+'</a>';
          }else{
            var more = '';
          }
          if(data.review_link){
            var rate = '<br><a href="'+data.review_link+'" class="rate_it" target="_blank">'+l_rate_it+' <img src="/images/icons/star.png" alt="" width="17" height="16" /></a>';
          }else{
            var rate = '';
          }
          if(data.renew){
            var renew = '<a href="/my/bill/recharge" class="rate_it text-danger" style="margin-left: 20px;">'+l_renew_license+'</a>';
          }else{
            var renew = '';
          }
          
          btn.popover({
            html: true,
            placement: 'bottom',
            title: data.article.article_title+'<button type="button" class="close" aria-label="Close" data-id="'+data.article.article_id+'"><span aria-hidden="true">&times;</span></button>',
            content: data.article.article_content+'<div class="modal-footer"><div class="text-center">'+prev+more+next+rate+renew+'</div></div>'
          });
          btn.data('loaded',1);
          $('.mpopover[data-id="'+data.article.article_id+'"]').trigger('click');
        }
      });
  });

  var hash = location.hash;
  if(hash.search('show=')!=-1){
    navid = hash.replace('#show=','');
    history.replaceState(false, false, window.location.pathname);
    $('.mpopover[data-id="'+navid+'"]').trigger('click');
  }
  $( window ).resize(function() {
    console.log(open_popover);
    //$('.mpopover[data-id="'+open_popover+'"]').tooltip('recalculatePosition');
    
  });
  var rtime;
var timeout = false;
var delta = 100;
$(window).resize(function() {
    rtime = new Date();
    if (timeout === false) {
        timeout = true;
        setTimeout(resizeend, delta);
    }
});

function resizeend() {
    if (new Date() - rtime < delta) {
        setTimeout(resizeend, delta);
    } else {
        timeout = false;
         console.log('Done resizing');
         if(open_popover){
      var op = open_popover;
      console.log(op);
      $('.mpopover[data-id="'+op+'"]').trigger('click');
      console.log(op);
      $('.mpopover[data-id="'+op+'"]').trigger('click');
    }
    }               
}
/*
$.fn.tooltip.Constructor.prototype.recalculatePosition = function(){
    var $tip = this.tip();//console.log($tip);
    //if($tip.hasClass('in')){console.log(1);
      var placement = typeof this.options.placement == 'function' ?
        this.options.placement.call(this, $tip[0], this.$element[0]) :
        this.options.placement

      var autoToken = /\s?auto?\s?/i
      var autoPlace = autoToken.test(placement)
      if (autoPlace) placement = placement.replace(autoToken, '') || 'top'

      $tip.addClass(placement)

      var pos          = this.getPosition()
      console.log(pos);
      var actualWidth  = $tip[0].offsetWidth
      var actualHeight = $tip[0].offsetHeight

      if (autoPlace) {
        var orgPlacement = placement
        var viewportDim = this.getPosition(this.$viewport)

        placement = placement == 'bottom' && pos.bottom + actualHeight > viewportDim.bottom ? 'top'    :
                    placement == 'top'    && pos.top    - actualHeight < viewportDim.top    ? 'bottom' :
                    placement == 'right'  && pos.right  + actualWidth  > viewportDim.width  ? 'left'   :
                    placement == 'left'   && pos.left   - actualWidth  < viewportDim.left   ? 'right'  :
                    placement

        $tip.removeClass(orgPlacement).addClass(placement)
      }

      var calculatedOffset = this.getCalculatedOffset(placement, pos, actualWidth, actualHeight)
      this.applyPlacement(calculatedOffset, placement)
    //}
  }
   */
});

