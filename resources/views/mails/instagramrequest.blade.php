<p>Un Gusto en saludarle! aqui le envio la iformacion de un cliente, para su gestion:</p>
<table style="width: 50%;font-family: verdana;font-size: 12px;">
	<thead>
		<tr>
			<th><div style="margin-left: 35%;">Usuario:</div></th>
			<th>Mensaje:</th>
		</tr>
	</thead>
	@foreach($timeline as $message)
		@if($message->from_user == '1769706132')
			@php $from = 'PlusUltra' @endphp
		@else
			@php $from = $message->insta_user->user_name_ig @endphp
		@endif
		<tr style="width: 50%;">
			<td><div style="margin-left: 35%;">{{$from}}</div></td>
			<td>
				<div style="width: 80%;margin-left: 10%;text-align:  justify;margin-top: 3%;margin-bottom: 3%;">
					{{$message->text_message}}
				</div>
			</td>
		</tr>
	@endforeach
</table>
@if($data->comment != null)
	<p><b>El cliente ha sido redireccionado por este comentario que envio:</b></p>
	{{$data->comment}}
@endif
