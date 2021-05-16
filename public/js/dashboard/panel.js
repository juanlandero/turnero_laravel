Vue.component('shifts-speciality', {
    props: [
        'id',
        'speciality',
        'total',
    ],
    template: `
        <div class="row text-center mb-3">
            <div class="col-8"><h6>{{ speciality }}</h6></div>
            <div class="col-4"><h6>{{ total }}</h6></div>                  
        </div>
    `,
})

var appPanel = new Vue({
    delimiters: ['${', '}'],
    el: '#app-panel-tickets',
    data: {
        panel: {
            isActive: false,
            menuChannel: null,
            panelChannel: null,
            office: null,
            user: null,
            userSpecialities:null
        },
        attending:{
            id: 0,
            status:'',
            shift: '-',
            speciality: '-',
            type: '-',
            time: '-',
            sex: '-',
            client: '-',
            number: '-',
        },
        // advisers: [],
        disabledButtons: {
            buttonNext: true,
            buttonAbandoned: true,
            buttonFinalized: true,
            // buttonReassigned: true,
            buttonConnect: true
        },
        userStatus: {
            text: 'Loading...',
            btnType: 'btn-light'
        }
    },

    methods: {
        // getListShift () {
        //     var _that = this
        //     this.shiftList = []

                            
        //     axios.post('shifts/get', {
        //         type: 1,
        //         userId: this.panel.user
        //     })
        //     .then(function (response) {
        //         response.data.forEach(shift => {

        //             if (shift.status == 1) {
        //                 _that.shiftList.push(shift)
        //             } else {
        //                 _that.attending.id          = shift.id
        //                 _that.attending.shift       = shift.shift
        //                 _that.attending.speciality  = shift.speciality
        //                 _that.attending.type        = shift.shift_type
        //                 _that.attending.time        = shift.time.substring(11, 19)
        //                 _that.attending.client      = shift.name_client
        //                 _that.attending.number      = shift.number_client
        //                 _that.attending.sex         = shift.sex_client
            
        //                 _that.disabledButtons.buttonNext = true
        //                 _that.disabledButtons.buttonAbandoned = false
        //                 _that.disabledButtons.buttonFinalized = false
        //                 _that.disabledButtons.buttonReassigned = false
        //             }
        //         });
        //     })
        //     .catch(function (error) {
        //         console.log(error)
        //     })
        // },

        // getListAdvisers () {
        //     var _that = this

        //     if (this.attending.id != 0) {
                
        //         axios.get('shifts/get-advisers?shift_id='+_that.attending.id)
        //         .then(function (response) {
        //             if (response.data.success) {
        //                 _that.advisers = response.data.objAdvisers
        //                 $('#reassignment-modal').modal('show')
        //             } else {
        //                 _that.notify(response.data.alert.type, response.data.alert.text, response.data.alert.icon)
        //             }
        //         })
        //         .catch(function (error) {
        //             console.log(error)
        //         })   
        //     } else {
        //         _that.notify('danger', ' No tiene un turno en proceso', 'far fa-times-circle')
        //     }
        // },

        setServiceOn () {
            var _that = this

            axios.post('shifts/get-data')
            .then(function (response) {
                if (response.data['channel'] == null) {
                    _that.notify('danger', 'No estás vinculado a una oficina', 'fas fa-user-times')
                }else{
                    _that.notify('info', 'Conectando... Espere.', 'fas fa-info-circle')
                    _that.panel.menuChannel = response.data['channel'].menu_channel
                    _that.panel.panelChannel = response.data['channel'].panel_channel
                    _that.panel.office = response.data['channel'].office_id
                    _that.panel.user = response.data['idUser']
                    // Especialidades con sus turnos
                    _that.panel.userSpecialities = response.data['specialities']
                    if (_that.panel.isActive == false) {
                        _that.pusher()
                        
                        setTimeout(() => {
                            _that.setUserOn()
                        }, 5000);
                    } else {
                        _that.notify('warning', 'Ya se està iniciando el servicio', 'fas fa-user-times')
                    }
                    
                }
            })
            .catch(function (error) {
                console.log(error)
            })
        },
        
        setUserOn () {
            var _that = this
                            
            axios.post('shifts/user-status')
            .then(function (response) {
                if (response.data.state) {
                    _that.notify(response.data.type, response.data.text, response.data.icon)
                } else {
                    _that.notify(response.data.type, response.data.text, response.data.icon)
                }

                _that.userBreak(1)
                _that.changeStateButton(false, true, true)
            })
            .catch(function (error) {
                console.log(error)
            })
        },

        userBreak (check) {
            var _that = this
            this.disabledButtons.buttonConnect = true
                            
            axios.post('shifts/break', {
                case: check
            })
            .then(function (response) {
                _that.userStatus.text = response.data.btnText
                _that.userStatus.btnType = response.data.btnType

                setTimeout(() => {
                    _that.disabledButtons.buttonConnect = false
                }, 6000);

                if (response.data.case == 2) {
                    _that.notify(response.data.type, response.data.text, response.data.icon)
                }   
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
            var menuChannelPusher = pusher.subscribe(this.panel.menuChannel)
            var panelChannelPusher = pusher.subscribe(this.panel.panelChannel)

            menuChannelPusher.bind('newShift', function(data) {
                _that.addShift(data)
            })

            panelChannelPusher.bind('nextShift', function(data) {
                _that.removeShift(data)
            })

            this.panel.isActive = true
        },

        addShift (dataChannel) {
            // var shift_id = dataChannel.idTicket
            var speciality_id = dataChannel.idSpeciality
            // var is_new = dataChannel.isNew
            
            this.panel.userSpecialities.forEach(speciality => {
                if (speciality.speciality_type_id == speciality_id) {
                    speciality.total += 1
                }
            });
        },

        removeShift(dataChannel){
            var speciality_id = dataChannel.idSpeciality
            
            this.panel.userSpecialities.forEach(speciality => {
                if (speciality.speciality_type_id == speciality_id) {
                    speciality.total -= 1
                }
            });
        },

        nextShift () {
            var _that = this
            this.changeStateButton(true, true, true)

            axios.post('shifts/next', {
                'panel_channel': _that.panel.panelChannel,
                'specialities': _that.panel.userSpecialities,
                'office': _that.panel.office
            })
            .then(function (response) {
                if (response.data.state == true) {
                    _that.attending.id          = response.data.shift.id
                    _that.attending.shift       = response.data.shift.shift
                    _that.attending.speciality  = response.data.shift.speciality
                    _that.attending.type        = response.data.shift.type
                    _that.attending.time        = response.data.shift.generate.substring(11, 19) // No da la hora exacta
                    _that.attending.client      = response.data.shift.client
                    _that.attending.number      = response.data.shift.number
                    _that.attending.sex         = response.data.shift.sex
                    _that.changeStateButton(true, false, false)

                } else {
                    _that.changeStateButton(false, true, true)
                }
                
                _that.notify(response.data.type, response.data.text, response.data.icon)
            })
            .catch(function (error) {
                console.log(error)
            })   

        },

        // reassignmentShift () {
        //     var data_form = $('#form-advisor').serializeArray()
        //     var _that = this

        //     if (data_form[2].value != 0) {
                
        //         axios.post('shifts/reassignment',{
        //             shift_id: _that.attending.id,
        //             send_id: data_form[1].value,
        //             recive_id: data_form[2].value,
        //             menu_channel: _that.panel.menuChannel
        //         })
        //         .then(function (response) {
        //             $('#reassignment-modal').modal('hide')
        //             if (response.data.state) {
        //                 _that.setClearShiftData()
        //             }
        //             _that.notify(response.data.type, response.data.text, response.data.icon)
        //         })
        //         .catch(function (error) {
        //             console.log(error)
        //         }) 
        //     } else {
        //         _that.notify('warning', 'Debe elegir un nuevo asesor.', 'fas fa-exclamation-triangle')
        //     }
        // },

        changeStatusShift (status) {
            var _that = this

            if (this.attending.id != 0) {
                axios.post('shifts/status', {
                    shiftId: this.attending.id,
                    typeStatus: status
                })
                .then(function (response) {
                    _that.setClearShiftData()
                    _that.changeStateButton(false, true, true)
                    _that.notify(response.data.type, response.data.text, response.data.icon)
                })
                .catch(function (error) {
                    console.log(error)
                })

            } else {
                this.notify('warning', ' No tiene un turno en proceso', 'fa fa-exclamation-triangle')
            }
        },

        setClearShiftData () {
            this.attending.id = 0
            this.attending.shift = '-'
            this.attending.speciality = '-'
            this.attending.type = '-'
            this.attending.time = '-'
            this.attending.client = '-'
            this.attending.number = '-'
            this.attending.sex = '-'            
        },

        changeStateButton (next, abandoned, finalized) {
            this.disabledButtons.buttonNext         = next
            this.disabledButtons.buttonAbandoned    = abandoned
            this.disabledButtons.buttonFinalized    = finalized
            // this.disabledButtons.buttonReassigned = true
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
