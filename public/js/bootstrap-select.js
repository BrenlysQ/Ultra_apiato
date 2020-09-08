$.fn.uchip_select = function(options) {
  var settings = $.extend( {
    'input' : '',
  }, options );
    var element = this;
    var opts =  this.find('.option');
    opts.each(function(){
      $(this).click(function(){
        element.find('.option.active').removeClass('active');
        $(this).addClass('active');
        var seldata = element.find('.selected_opt');
        var idselected = $(this).attr('data-id');
        seldata.html($(this).html());
        $(settings.input).val(idselected);
        seldata.attr('data-value',idselected);
      });
    });

};
