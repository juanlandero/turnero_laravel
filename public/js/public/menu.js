Vue.component('speciality-button', {
    props: [
        'id',
        'speciality',
        'class_btn',
    ],
    template: `
        <div class="col-sm-6 col-md-6 col-lg-4 mb-3 text-center">
            <button class="btn btn-light btn-lg btn-block" type="button" data-toggle="modal" data-target="#client-modal" @click="$emit('press-button')">
                <p><i :class="class_btn"></i></p>
                {{ speciality }}
            </button>
        </div>
    `,
})

var appTodo = new Vue({
    delimiters: ['${', '}'],
    el: '#app-ticket-generator',
    data: {
        menu: [],
        ticket:{
            client_number:null,
            speciality: 0,
            sex: null
        },
        count: 0
    },
    mounted: function(){
        this.getSpeciality()
    },
    methods: {
        getSpeciality () {
            var _that = this

            axios.post('public-shift/get-speciality', {
                firstName: 'Fred',
                lastName: 'Flintstone'
            })
            .then(function (response) {
                _that.menu = response.data
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        setSpecialityTicket (speciality) {
            // Eliminar después de probar
            console.log(speciality)

            if (this.ticket.speciality != 0) {
                this.clearTicketData()
            }

            this.ticket.speciality = speciality
        },

        verifyClientNumber (){

            var _that = this

            if (this.ticket.client_number != null) {
                axios.post('verify-client', {
                    client: this.ticket.client_number
                })
                .then(function (response) {
                    console.log(response.data)

                    _that.ticket.sex = response.data['client'].sex

                    _that.createTicket()
                })
                .catch(function (error) {
                    console.log(error);
                })
            } else {
                alert('Debe insertar su número de cliente, si no cuenta con uno presione. NO')
            }
        },

        createTicket () {
            var _that = this

            axios.post('new-ticket', _that.ticket)
            .then(function (response) {
                _that.clearTicketData()
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        clearTicketData(){
            this.ticket.client_number = null
            this.ticket.sex = null
            this.ticket.speciality = 0
        }
    }
})
