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
        ticket:{
            channel: null,
            speciality: 0,
            has_number: true,
            client_number:null,
            sex: null
        }
    },
    mounted: function(){
        this.getSpecialities()
        this.getChannel()
    },
    methods: {

        getChannel(){
            var _that = this

            axios.post('shift/get-channel')
            .then(function (response) {
                _that.ticket.channel = response.data.menu_channel
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        getSpecialities () {
            var _that = this

            axios.post('shift/get-speciality')
            .then(function (response) {
                _that.menu = response.data
            })
            .catch(function (error) {
                console.log(error);
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
                axios.post('verify-client', {
                    client: this.ticket.client_number
                })
                .then(function (response) {
                    // console.log(response.data)

                    if (response.data.success == 'true') {
                        _that.ticket.sex = response.data['client'].sex
                        _that.createTicket()
                    } else{
                        alert('No existe su numero de cliente')
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
            } else {
                alert('Debe insertar un número de cliente, si no cuenta con uno presione. NO')
            }
        },

        createTicket () {
            var _that = this

            axios.post('new-ticket', _that.ticket)
            .then(function (response) {
                _that.clearTicketData()
                $('#client-modal').modal('hide')
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        setSex (sex) {
            this.ticket.sex = sex
            $('#client-modal').modal('hide')
            this.createTicket()
            this.printTicket()
        },

        printTicket(){
            //Imprimir ticket
            this.clearTicketData()
        },

        clearTicketData (){
            this.ticket.speciality = 0
            this.ticket.client_number = null
            this.ticket.has_number = true
            this.ticket.sex = null
        },
    }
})
