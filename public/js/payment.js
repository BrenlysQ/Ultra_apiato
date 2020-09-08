function sendheight(){
	window.parent.postMessage($('#app').height(), "*");
}
function gateway_binder(){
	var gateway = $('#selected_pgateway').val();
	console.log(gateway);
	bind_tdc();
	bind_banks();
}
var uchip = new $.Uchip();
$( document ).ready(function() {
	sendheight();
	gateway_binder();
  $(".radiopp").click(function(){
		$( ".ppaymentdiv" ).toggle("slow", function() {
			sendheight();
		});
  });
  $('#btnchpgw').click(function(){
    $('.paymentwindow').slideToggle();
  });
  $('.pgateways .pgateway').click(function(){
    $('.pgateways .pgateway.active').removeClass('active');
    $(this).addClass('active');
  });
  $('#cancelchpgw').click(function(){
    $('.paymentwindow').slideToggle();
  });
  $('#chpgw').click(function(){
    var pgateway = $('.pgateways .pgateway.active').attr('data-id');
    if($('#selected_pgateway').val() != pgateway){
      $('.paymentwindow').slideToggle();
      uchip.AsyncCall({
        url : '/payments/pgateway',
        method : 'post',
        data : '_token=' + window.Laravel.csrfToken + '&invid=' + $('#invid').val() + '&pid=' + pgateway,
        beforesend : function(){
          $('#pgmodal').removeClass("hidden").addClass("shown");
          //$().show();
        },
        callback : function(response){
          var pgmodal = $('#pgmodal');
          //$('#form_container').html(response);
          //$('#form_container').html(response);
          $('#selected_pgateway').val(pgateway);
          $('#pgmodal').removeClass('shown').addClass("hidden");
          gateway_binder();
					sendheight();
        }
      });
    }
  });
});
$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd-mm-yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);
//Esto deberia estar en archivos separados por ejemplo bankstransfer.js
//Y ebe ser uobjeto o plugin e jquery instanciable, paracedioa a Uchip
function send_bank_transfer(data){
	uchip.AsyncCall({
		url : '/bankstransfer/add',
		method : 'post',
		data : '_token=' + window.Laravel.csrfToken + '&' + data,
		beforesend : function(){
			$('#paymodal').removeClass("hidden").addClass("shown");
			//$().show();
		},
		callback : function(response){
			$('.roweqheight').html(response);
			$('#paymodal').removeClass('shown').addClass("hidden");
		}
	});
}
function send_tdc_payment(data){
	uchip.AsyncCall({
		url : '/creditcard/process',
		method : 'post',
		data : '_token=' + window.Laravel.csrfToken + '&' + data,
		beforesend : function(){
			$('#paymodal').removeClass("hidden").addClass("shown");
			//$().show();
		},
		callback : function(response){
			$('.roweqheight').html(response);
			$('#paymodal').removeClass('shown').addClass("hidden");
		}
	});
}

function bind_tdc(){
	$('#btn-mkpaytdc').click(function(){
		console.log('gateway');
    var data = $('#payment-formtdc').serialize();
		if($('#payment-formtdc').valid()){
			data += '&invid=' + $('#invid').val() + '&totpay=' + $('#totpay').val() + "&pgateway=" + $('#selected_pgateway').val();
			console.log(data);
			send_tdc_payment(data);
			return 'yes';
		}else{
			console.log("Not valid");
		}

  });
	$('#payment-form').validate({
    errorClass: 'reqpay',
		ignore: ".ignore"
  });
}
function bind_banks(){
  $('#btn-mkpaybt').click(function(){
    var data = $('#payment-formbankt').serialize();
		if($('#payment-formbankt').valid()){
			data += '&invid=' + $('#invid').val() + '&totpay=' + $('#totpay').val() + "&pgateway=" + $('#selected_pgateway').val();
			send_bank_transfer(data);
			return 'yes';
		}else{
			console.log("Not valid");
		}

  });
  $('.transwbtn').click(function(){
    $('.transwindow').slideToggle();
  });
  $('#bankselect').uchip_select({
    input : '#bankdata'
  });
  $( "#input_pd" ).datepicker({
      minDate: "-3M",
      maxDate: 0,
      numberOfMonths: 1,
      autoclose: true,
      zIndexOffset: 1100,
      dateFormat : 'd, M',
      onSelect: function(date) {
        var pickdate = $(this).datepicker( "getDate" );
        var paydate = moment(pickdate).format('YYYY-MM-DD');
        $('#payment_date').val(paydate);
      }
  });
	//var form = $('#payment-form');
	$('#payment-formtdc').validate({
    errorClass: 'reqpay',
		ignore: ".ignore"
  });
  $('#payment-formbankt').validate({
    errorPlacement: function(error, element) {
      error.insertAfter(element.parent());
    },
    errorClass: 'reqpay',
		ignore: ".ignore"
  });
}
