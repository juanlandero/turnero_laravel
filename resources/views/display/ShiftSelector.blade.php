<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elige un turno</title>

    <link href="{{ asset('css/public-css.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
</head>
<body>
	<div class="full-display">
        <!-- CONTENIDO -->
		<main class="app-public-display" id="app-ticket-generator">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-12">
                        <h1>Bienvenido</h1>
                    </div>
                    <div class="col-12">
                        <h2 >¿En qué podemos ayudarte?</h2>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <speciality-button v-for="button in menu" 
                        v-bind:key="button.id"
                        :id="button.id"
                        :speciality="button.speciality"
                        :class_btn="button.class_btn"
                        @press-button="setSpecialityTicket(button.id)"
                    ></speciality-button>
                </div>
            </div>

            @include('display.modals.NumberClient')
		</main>     
    </div>

    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/plugins/bootstrap-notify.min.js') }}"></script>
    
    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/public/menu.js') }}"></script>
</body>
</html> 