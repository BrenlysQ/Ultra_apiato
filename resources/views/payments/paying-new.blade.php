<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="paying-card">
					<div class="paying-card-header">
						<div class="paying-card-avatar">
							<img src="img/user.png" alt="">
							<p class="hidden-xs">Usted ha iniciado sesion como <strong>Plus Ultra</strong></p>
						</div>
						<div class="paying-card-avatar">
							<a href="#"><i class="fa fa-sign-out circlei"></i>Salir</a>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="paying-card-aside">
								<h3>Información de la compra</h3>

								<ul>
									<li>
										<h5>Comercio:</h5>
										<h4>Carlos el silbon</h4>
									</li>
									<li>
										<h5>Código de compra:</h5>
										<h4>785275758</h4>
									</li>
									<li>
										<h5>Pendiente por pagar:</h5>
										<h4>93772 VEF</h4>
									</li>
									<li>
										<h5><strong>Total:</strong></h5>
										<h4><strong>93772 VEF</strong></h4>
									</li>
									<li class="hidden-xs">
										<div class="boton_radio">
		                                    <label class="label--radio" for="roundtrip">
		                                        <input type="radio" name="trip" id="roundtrip" class="radio" value="2">
		                                    </label>
		                                    <p>Pago Total</p>
                                		</div>

                                		<div class="boton_radio">
		                                    <label class="label--radio" for="roundtrip">
		                                        <input type="radio" name="trip" id="roundtrip" class="radio" value="2">
		                                    </label>
		                                    <p>Pago Parcial</p>
                                		</div>
									</li>

								</ul>
							</div>
						</div>
						<div class="col-md-8">
							<div class="paying-card-data">
								<h3>Datos de Transferencia</h3>
								<div class="row">
									<div class='col-md-12 form-group required'>
										<label class='control-label'>Banco destino</label>
										<div class="dropdown uchip_select" id="bankselect">
								            <button class="btn dropdown-toggle" type="button" id="dropdownMenu1"
								              data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								              <span class="pull-left selected_opt" data-value="">Seleccione un banco</span>
								              <span class="caret"></span>
								            </button>

								            <input id="invoiceid" name="invoiceid" value="" type='hidden'>
								            <ul class="dropdown-menu">

								                <li class="option">
								                  <a href="#"><img src="img/banco.png" width="24px" />Banesco</a>
								                </li>

								                <li class="option">
								                  <a href="#"><img src="img/banco.png" width="24px" />Banesco</a>
								                </li>

								                <li class="option">
								                  <a href="#"><img src="img/banco.png" width="24px" />Banesco</a>
								                </li>

								            </ul>
								        </div>
									</div>
									<div class='col-md-5 form-group required'>
							          	<label class='control-label'>Fecha</label>
								        <div class="input-group">
								            <span class="input-group-addon">
								              <i class="fa fa-calendar fa-fw"></i>
								            </span>
								            <input class='form-control' id="input_pd" size='4' type='text' data-rule-required="true" data-msg-required="Seleccione una fecha.">
								            <input id="payment_date" value='' name="payment_date" type='hidden'>
								        </div>
							        </div>
							        <div class='col-md-7 form-group required'>
							          <label class='control-label'>Tipo de transacción</label>
							          <div class="input-group">
							            <span class="input-group-addon">
							              <i class="fa fa-credit-card-alt fa-fw"></i>
							            </span>
							            <select class="form-control" name="type" id="type">
							              <option selected="" value="1">Transferencia</option>
							              <option value="2">Deposito</option>
							            </select>
							          </div>
							        </div>
							        <div class='col-xs-12 form-group required'>
							        	<div>
								            <label class='control-label'>Número de confirmación</label>
								            <input class='form-control' data-rule-required="true"
								            data-msg-required="Introduzca el numero de confirmacion"
								            id="confirmation_number" name="confirmation_number" size='4' type='text'>
							         	</div>
							        </div>

							        <div class="col-md-12 display-flex">
										<div class="boton_radio">
		                                    <label class="label--radio" for="roundtrip">
		                                        <input type="radio" name="trip" id="roundtrip" class="radio" value="2">
		                                    </label>
		                                    <p>Pago Total</p>
                                		</div>

                                		<div class="boton_radio">
		                                    <label class="label--radio" for="roundtrip">
		                                        <input type="radio" name="trip" id="roundtrip" class="radio" value="2">
		                                    </label>
		                                    <p>Pago Parcial</p>
                                		</div>
							        </div>

							        <div class="col-md-12">
							        	<a href="#" class="btn btn-decrarar"><i class="fa fa-refresh"></i>Declarar Pago</a>
							        </div>

							        <div class="col-md-12">
							        	<div class="paying-card-logo">
							        		<img src="img/ultrapagos.png" alt="">
							        	</div>
							        </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
