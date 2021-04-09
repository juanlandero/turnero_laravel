<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Pantalla de turnos</title>

		<link rel="stylesheet" href="css/public-css.css">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
		<link href="{{ asset('css/all.css') }}" rel="stylesheet">

</head>
<body>
	
	<div class="full-display">
	
		<main class="app-public-display">
			<!-- ENCABEZADO -->
			@include('display.components.Header')

			<!-- CONTENIDO -->
			<div class="row height-content" style="margin: 0px;">
				<!-- PANEL IZQUIERDO -->
				@include('display.components.ShiftList')

				<!-- PANEL DERECHO -->
				<div class="col-8 text-center">
					<div class="row">
		
						<div class="col-6">
							<h1><i class="fa fa-dashboard"></i> E002</h1>
							<p>Turno</p>
						</div>
						<div class="col-6">
							<h1>02</h1>
							<p>Caja</p>
						</div>

						<!-- CARRUSEL -->
						@include('display.components.Carousel')
					</div>
				</div>

			</div>

		</main>

	</div>

	<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/main.js') }}"></script>
	<script src="{{ asset('js/popper.min.js') }}"></script>
<script>

	$('.carousel').carousel()

</script>

</body>
</html>