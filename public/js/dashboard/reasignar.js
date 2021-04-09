var appPanel = new Vue({
    delimiters: ['${', '}'],
    el: '#app-panel-tickets',
    data: {
        menuChannel: null,
        panelChannel: null,
        advisers: [],
    },

    mounted() {
        this.setServiceOn();
    },
    methods: {
        getListAdvisers (shift) {
            var _that = this
            var shift_id = $('#shift_id').val(shift)

            axios.get('get-advisers?shift_id='+shift_id)
            .then(function (response) {
                if (response.data.success) {
                    _that.advisers = response.data.objAdvisers
                    $('#reassignment-modal').modal('show')
                } else {
                    _that.notify(response.data.alert.type, response.data.alert.text, response.data.alert.icon)
                }
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
