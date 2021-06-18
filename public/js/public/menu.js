Vue.component('speciality-button', {
    props: [
        'id',
        'speciality',
        'class_btn',
    ],
    template: `
        <div class="col-sm-6 col-md-6 col-lg-4 mb-3 text-center">
            <button class="btn btn-light btn-lg btn-block" type="button" @click="$emit('press-button')">
                <p><i :class="class_btn"></i></p>
                {{ speciality }}
            </button>
        </div>
    `,
})

var appMenu = new Vue({
    delimiters: ['${', '}'],
    el: '#app-ticket-generator',
    data: {
        menu: [],
        office:{
            menuChannel: null,
            userChannel: null,
            address: null,
            date: null,
            twoModal: false,
            changeModal:false
        },
        ticket:{
            shift: null,
            speciality: 0,
            has_number: false,
            client_number:null,
            sex: null,
            date: null,
            hour: null
        },
        token: $('meta[name="csrf_token"]').attr('content')
    },
    mounted: function(){
        this.getData()
        setTimeout(() => { this.pusher() }, 3000);
    },
    methods: {

        getData () {
            var _that = this
            const fecha = new Date();
            const meses = [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre",
              ]

            this.office.date = fecha.getDate() +"/"+ meses[fecha.getMonth()] +"/"+fecha.getFullYear()

            axios.get('shifts/get-data')
            .then(function (response) {
                _that.menu = response.data.specialities
                _that.office.menuChannel = response.data.menu_channel
                _that.office.userChannel = response.data.user_channel
                _that.office.address = response.data.address
            })
            .catch(function (error) {
                console.log(error)
            })
        },

        pusher () {        
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true
    
            var _that = this
            var pusher = new Pusher('edfdfd25a99f00107c87', {
                cluster: 'us2'
            })
            var menuChannelPusher = pusher.subscribe(this.office.userChannel)

            menuChannelPusher.bind('userOnline', function(data) {
                if (_that.menu != null) {
                    _that.getData()
                }
            })
        },

        setSpecialityTicket (speciality) {
            if (this.ticket.speciality != 0) {
                this.clearTicketData()
            }

            this.ticket.speciality = speciality
            $('#client-modal').modal('show')
            $('#client-modal').on('shown.bs.modal', function () {
                $('#client').trigger('focus')
            })
        },

        verifyClientNumber (){
            var _that = this

            //En caso de que entre sin especialidad
            if (this.ticket.speciality == 0) {
                this.clearTicketData()
                $('#client-modal').modal('hide')
            }

            if (this.ticket.client_number != null) {
                axios.post('shifts/get-client', {
                    client: this.ticket.client_number
                })
                .then(function (response) {
                    if (response.data.success == 'true') {
                        _that.client.has_number = true
                        _that.setSex(response.data['client'].sex)
                    } else{
                        _that.notify("danger", "Número de cliente incorrecto.", "fa fa-times-circle")
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
            } else {
                this.notify("warning", "Inserte su número de cliente.", "fa fa-exclamation-circle")
            }
        },

        createTicket () {
            var _that = this

            if (_that.ticket.speciality != 0) {
                axios.post('shifts/new', {
                    speciality: _that.ticket.speciality,
                    has_number: _that.ticket.has_number,
                    client_number: _that.ticket.client_number,
                    sex: _that.ticket.sex,
                    channel: _that.office.menuChannel
                })
                .then(function (response) {
                    $('#shift').html(response.data.ticket.shift)
                    $('#speciality').html(response.data.ticket.speciality)
                    $('#hours').html(response.data.ticket.hora.substring(11, 19))

                    printJS({
                        printable:'ticket',
                        type:'html'
                    });

                    _that.clearTicketData()
                })
                .catch(function (error) {
                    console.log(error)
                    _that.clearTicketData()
                })
            }
        },

        setSex (sex) {
            this.ticket.sex = sex
            $('#client-modal').modal('hide')
            this.createTicket()
            
            if (this.office.twoModal == true) {
                this.office.changeModal = false
            }
        },

        clearTicketData () {
            this.ticket.speciality = 0
            this.ticket.client_number = null
            this.ticket.has_number = false
            this.ticket.sex = null
        },

        notify (type, message, icon) { 
            $.notify({
                title: "",
                message: message,
                icon: icon 
            },{
                newest_on_top: true,
                type: type,
                z_index: 1100,
            })
        },

        actionModal (action) {
            var _that = this

            if (this.office.twoModal) {
                // DOS MODALES: Código y Sexo
                switch (action) {
                    case 1:
                        this.verifyClientNumber()
                        break;
                
                    case 2:
                        this.office.changeModal = true
                        break;

                    default:
                        _that.notify("danger", "Error al lanzar el modal", "fa fa-times-circle")
                        break;
                }  
            } else {
                // UN SOLO MODAL: Código
                switch (action) {
                    case 1:
                        this.verifyClientNumber()
                        break;

                    case 2:
                        this.setSex('N/A')
                        break;
                
                    default:
                        _that.notify("danger", "Error al lanzar el modal", "fa fa-times-circle")
                        break;
                }
            }
            
           
        }
    }
})
