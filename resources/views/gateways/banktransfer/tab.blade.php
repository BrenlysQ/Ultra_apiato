<h5 class="pnr-title">Nuestros Bancos</h5>
<ul class="banks-list">
	@foreach($banklist as $i => $bank)
	<li>
		<div class="banks-bank">
			<div class="banks-item">
				<img src="{{$bank->img_url}}">
			</div>
			<div class="banks-item">
				<h4 class="banks-bank-title">{{$bank->name}}</h4>
				<p class="banks-text"><strong>A nombre de:</strong> Plus Ultra C.A</p>
				<p class="banks-text"><strong>Tipo de cuentas:</strong> {{$bank->account_type}}</p>
				<p class="banks-text"><strong>Numero de cuentas:</strong> {{$bank->account_number}}</p>
				<p class="banks-text"><strong>Rif:</strong> {{$bank->rif}}</p>
				<p class="banks-text"><strong>Email:</strong> {{$bank->email}}</p>
			</div>
		</div>
	</li>
	<hr>
	@endforeach
	<li class="pnr-info hidden-xs">
		<p class="pnr-pass">
			<span><i class="fa fa-exclamation"></i></span> Desde tu banca por internet realiza la tranferencia a las cuentas arriba descritaas de lunes a viernes de 8:00 a 21:00 hrs y los sabados y domingos hasta las 17:00 hrs y que preferiblemente se encuentre dentro de las siguientes 4 horas despues de realizar la reserva.
		</p>
	</li>
</ul>
