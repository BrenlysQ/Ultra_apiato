
(function ($) { //an IIFE so safely alias jQuery to $
    $.Uchip = function (options) {
        this.settings = $.extend( {
          'form' : '#form_request_info', //Id del del formulario definido por defecto en el la vista includes/form_request_info
          'div' : '#div_request_info' //Div donde esta contenido el formulario vista includes/form_request_info
        }, options );
        this.form_html = $(this.settings.div).html();
        this.form_obj = null;
        this.url = $(this.settings.form).attr('action');
        console.log('this.url: ' + this.url);
        $(this.settings.div).remove();
    };
    $.Uchip.prototype = {
        AsyncCall: function(options){
          var settings = $.extend( {
            'url' : this.url,
            'data' : null,
            'method' : 'get',
            callback : function(response){},
            beforesend : function(){}
          }, options );
            $.ajax({
                url:   settings.url,
                type:  settings.method,
                data : settings.data,
                beforeSend: function(){
                  settings.beforesend();
                },
                success:  function (response) {
                    settings.callback(response);
                },
                error : function( jqXHR, textStatus, errorThrown){
                  /*if(jqXHR.status == 401){
                    swal2({
                      title: 'Error!',
                      text: 'La session ha expirado',
                      type: 'error',
                      confirmButtonText: 'Aceptar',
                      showCancelButton: true,
                    }).then(function () {
                        window.location.href='login';
                      })
                  }*/
                }
            });
        },
        LoginModal: function(form){
          var html = '<div id="lfcont">' + $('#modal-login').html() + '</div>';
          var obj = this;
          swal2({
            html: html,
            width : 450,
            showCancelButton: false,
            showConfirmButton: false,
            onOpen: function(){
              BindModalLogin(obj);
            }
          })
        }
    };
}(jQuery));
