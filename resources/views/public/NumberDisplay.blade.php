<!DOCTYPE html>
<html lang="es">
<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Pantalla de turnos</title>

		<link href="{{ asset('css/all.css') }}" rel="stylesheet">
		<link href="{{ asset('css/public-css.css') }}" rel="stylesheet">

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://js.pusher.com/7.0/pusher.min.js"></script>


		<style>
			crr {
				max-width: 100%;
				height: auto;
			}
		</style>
</head>
<body>
	
	<div class="full-display" id="app-public-display">
	
		<main class="app-public-display" v-if="serviceOn">
			<!-- ENCABEZADO -->
            <div class="app-public-display-title">
                <div>
                    <img src="{{ asset('img/madero-logo.jpeg') }}" height="20px" alt="">
                </div>
            
                <div><h1>Bienvenidos</h1></div>
            
                <div><h5 class="text-success">${ hour }</h5></div>
            </div>

			<!-- CONTENIDO -->
			<div class="row height-content" style="margin: 0px;">
				<!-- PANEL IZQUIERDO -->
                <div class="col-4">
                    <div class="roew tile mb-2">
                        <div class="row text-center">
                            <div class="col line-head"><h3>Turno</h3></div>
                            <div class="col line-head"><h3>Caja</h3></div>
						</div>
                        <div class="row text-center">
							<div class="col text-success"><h1>${ attending.shift }</h1></div>
							<div class="col text-success"><h1>${ attending.box }</h1></div>
                        </div>
					</div> 

					<div class="tile" style="height: 360px; overflow-y: hidden">
                        <item-shift v-for="shift in shiftList"
                            v-bind:key = "shift.id"
                            :id = "shift.id"
                            :shift = "shift.shift"
                            :box = "shift.box_name"
                        ></item-shift>
                    </div> 
                </div>


				<!-- PANEL DERECHO -->
				<div class="col-8 text-center">
					<div class="row">
		
						<div class="col-6">
							<h1><i class="fa fa-dashboard"></i>${ attending.shift }</h1>
							<p>Turno</p>
						</div>
						<div class="col-6">
							<h1>${ attending.box }</h1>
							<p>Caja</p>
						</div>
					</div>
					<div class="row justify-content-center">
						<!-- CARRUSEL -->
						<div class="col-11">
							@include('public.components.Carousel')
						</div>
					</div>
				</div>
			</div>

		</main>


		<div class="row align-items-center justify-content-center" v-else style="height: 450px">
			<div class="col-5 ">
				<button class="btn btn-primary btn-lg btn-block" type="button"  v-on:click="pusher()">Iniciar servicio</button>
			</div>
		</div>
	</div>
	
	{{-- Scripts --}}
	<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	
	<script src="{{ asset('js/axios.js') }}"></script>
	<script src="{{ asset('js/vue.js') }}"></script>
	<script src="{{ asset('js/public/display.js') }}"></script>

	<script>
		$('.carousel').carousel()
	</script>
</body>
</html>