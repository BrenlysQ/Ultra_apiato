function sendheight(){
	window.parent.postMessage($('#app').height(), "*");
	console.log($('#app').height());
}

var uchip = new $.Uchip();
$( document ).ready(function() {
	sendheight();
	bind_screen();
});
function form_screen(options){
	var settings = $.extend( {
		'toshow' : '',
		onshow : function(){},
	}, options );
	$('.account-form .panel-body.active').slideUp( "fast", function() {
    $(this).removeClass('active');
		$(settings.toshow).slideDown("fast", function(){
			$(this).addClass('active');
			sendheight();
			settings.onshow();
		});
  });
}
function reset_form_reset(response){
	form_screen({toshow:'#form_sendcode'});
	$('#token_reset').val(response.token)
}
function bind_screen(){
	$('#form-sendcode').validate();
	$('#form-reset').validate();
	$('.btn-reset-pass').click(function(){
		var button = $(this);
		if($('#form-reset').valid()){
			uchip.AsyncCall({
	      url : '/reset_password',
	      method : 'post',
	      data : '_token=' + window.Laravel.csrfToken + '&' + $('#form-reset').serialize(),
	      beforesend : function(){
					button.prepend('<i class="fa fa-spinner fa-spin"></i>');
					button.addClass('disabled');
	      },
	      callback : function(response)
				{
					response = JSON.parse(response);
					if(response.error !== true){
						console.log('Muestro');
						reset_form_reset(response);
					}else{
						$('.address-incorrect').slideDown()
					}
					button.find('i').remove();
					button.removeClass('disabled');
	      }
	    });
		}
	});
	$('#new-account').validate();
	$('#btnnewacc').click(function(){
		if($('#new-account').valid()){
			var button = $(this);
			uchip.AsyncCall({
	      url : '/register',
	      method : 'post',
	      data : '_token=' + window.Laravel.csrfToken + '&' + $( "#new-account" ).serialize(),
	      beforesend : function(){
					button.prepend('<i class="fa fa-spinner fa-spin"></i>');
					button.addClass('disabled');
	      },
	      callback : function(response)
				{
	        if(response != 'error'){
						window.location.href = response;
					}else{

					}
	      }
	    });
		}else{
			sendheight();
		}
		return;

	});
	$('.form-handler').click(function(){
		form_screen({toshow:$(this).attr('href')});
	});
	$('#btn-newpass').click(function(){
		if($('#form-sendcode').valid()){
			var button = $(this);
			uchip.AsyncCall({
	      url : '/reset_passwordact',
	      method : 'post',
	      data : '_token=' + window.Laravel.csrfToken + '&' + $( "#form-sendcode" ).serialize(),
	      beforesend : function(){
					button.prepend('<i class="fa fa-spinner fa-spin"></i>');
					button.addClass('disabled');
	      },
	      callback : function(response)
				{
					button.find('i').remove();
					button.removeClass('disabled');
					response = JSON.parse(response);
					if(response.error === undefined){
						form_screen({
							toshow:'#div_form',
							onshow : function(){
								$('.pwd-changed').show();
								$('.sent-succes').show();
								$('.incorrect-reset').hide();
							}
						});
					}else{
						$('.sent-succes').hide();
						$('.incorrect-reset').show();
					}
	      }
	    });
		}
	});
	$('.btn-login').click(function(){
		var button = $(this);
		uchip.AsyncCall({
      url : '/login',
      method : 'post',
      data : '_token=' + window.Laravel.csrfToken + '&' + $( "#form-signin" ).serialize(),
      beforesend : function(){
				button.prepend('<i class="fa fa-spinner fa-spin"></i>');
				button.addClass('disabled');
      },
      callback : function(response)
			{
        if(response != 'error'){
					window.location.href = response;
				}else{
					alert("Usuario o clave incorrecto")
				}
      }
    });
	});
}
