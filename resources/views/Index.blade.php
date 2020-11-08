<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Inicio | Madero</title>     
        
        <link rel="stylesheet" href="css/public-css.css">
		<link href="{{ asset('css/all.css') }}" rel="stylesheet">
    </head>
    <body>
        <section class="material-half-bg">
            <div class="cover"></div>
        </section>
        <section class="login-content">
            {{-- <div class="logo">
                <h1>Madero</h1>
            </div> --}}
            <div class="login-box" id="app-login">
                <form class="login-form" v-on:submit.prevent="verifyPublicAccess">
                    @csrf
                    <h3 class="login-head"></i>Bienvenido</h3>
                    <div class="form-group mt-5">
                        <input class="form-control text-center form-control-lg" type="text" v-model="office" v-on:click="text_result=null" placeholder="Ingresa la clave de acceso" autofocus autocomplete="off">
                    </div>
                    <div class="form-group btn-container mt-4">
                        <button class="btn btn-primary btn-block">Entrar</button>
                    </div>
                    <div class="form-group btn-container mt-4">

                        <p class="text-danger text-center">${text_result}</p>
                    </div>
                </form>
            </div>
        </section>

    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/vue.js') }}"></script>

    <script>
var appLogin = new Vue({
    delimiters: ['${', '}'],
    el: '#app-login',
    data: {
        office: null,
        text_result: null
    },
    methods: {
        verifyPublicAccess (){
            var _that = this
        
            axios.post('access', {
                office: this.office
            })
            .then(function (response) {
                if (response.data.success == "true") {
                    location.href=response.data.text
                } else {
                    _that.text_result = response.data.text
                }
            })
            .catch(function (error) {
                console.log(error);
            })
         
        },
    }
})
    </script>
    </body>
</html>
