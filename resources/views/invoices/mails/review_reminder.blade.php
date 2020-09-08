<p>Estimado {{$data->usersatdata->name}}, ha realizado una compra la cual espera por su calificacion.</p>
<br>
<br>
<p>Para nosotros es importante que califique nuestros vendedores para asi mejorar el servicio prestado. <br>
Detalles de la compra: <br>
Fecha: {{$data->created_at}} <br>
Total pagado: {{$data->total_paid}} <br>
Aliado vendedor: {{$data->freelance->name}} {{$data->freelance->lastname}} <br>
</p>
