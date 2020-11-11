Vue.component('item-my-list', {
    props: [
        'id',
        'shift',
        'type',
        'speciality',
    ],
    template: `
        <div class="row text-center mb-2">
            <div class="col-4"><h6>{{ shift }}</h6></div>
            <div class="col-4"><h6>{{ type }}</h6></div>
            <div class="col-4"><h6>{{ speciality }}</h6></div>                  
        </div>
    `,
})

var appPanel = new Vue({
    delimiters: ['${', '}'],
    el: '#app-panel-tickets',
    data: {
        menuChannel: null,
        panelChannel: null,
        advisors: [],
    },

    mounted() {
        this.setServiceOn();
    },
    methods: {
        getListAdvisors (shift) {
            var _that = this

            axios.get('get-advisors')
            .then(function (response) {
                _that.advisors = response.data
                $('#reassignment-modal').modal('show')
                $('#shift_id').val(shift)
            })
            .catch(function (error) {
                console.log(error)
            })
        },

        setServiceOn () {
            var _that = this

            axios.post('get-data')
            .then(function (response) {
                if (response.data['channel'] == null) {
                    _that.notify('warning', 'Error! Intentalo más tarde.', 'fa fa-exclamation-triangle')
                }else{
                    _that.menuChannel = response.data['channel'].menu_channel
                    _that.panelChannel = response.data['channel'].panel_channel
                    _that.user = response.data['idUser']
                }
            })
            .catch(function (error) {
                console.log(error)
            })
        },

        reassignmentShift () {
            var data_form = $('#form-advisor').serializeArray()
            var _that = this

            console.log(data_form)

            if (data_form[2].value != 0) {
                
                axios.post('reassignment',{
                    send_id: data_form[1].value,
                    shift_id: data_form[3].value,
                    recive_id: data_form[2].value,
                    menu_channel: _that.menuChannel
                })
                .then(function (response) {
                    $('#reassignment-modal').modal('hide')
                    _that.notify(response.data.type, response.data.text, response.data.icon)
                    setTimeout(() => {
                        location.reload()
                    }, 1500);
                })
                .catch(function (error) {
                    console.log(error)
                }) 
            } else {
                _that.notify('info', 'Debe elegir un nuevo asesor.', 'fa fa-info-circle')
            }
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
        }
    }
})
