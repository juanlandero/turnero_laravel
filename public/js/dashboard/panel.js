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
        isActive: false,
        menuChannel: null,
        panelChannel: null,
        user: null,
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
        shiftList:[]
    },

    methods: {
        getListShift (dataChannel) {
            var _that = this
                            
            axios.post('get-shift-advisor', {
                type: 1,
                userId: this.user
            })
            .then(function (response) {
                _that.shiftList = response.data
                // console.log(response.data)
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        setServiceOn () {
            var _that = this

            axios.post('get-user')
            .then(function (response) {

                if (response.data['channel'] == null) {
                    $.notify({
                        title: "  ",
                        message: " Necesita ser vinculado con una oficina.",
                        icon: 'fa fa-exclamation-circle' 
                    },{
                        allow_dismiss: false,
                        newest_on_top: true,
                        z_index: 10310,
                        type: "warning"
                    })
                    // alert('Necesita ser vinculado con una oficina')
                }else{
                    _that.menuChannel = response.data['channel'].menu_channel
                    _that.panelChannel = response.data['channel'].panel_channel
                    _that.user = response.data['idUser']
                    _that.pusher()
                    _that.getListShift()
                }

            })
            .catch(function (error) {
                console.log(error);
            })
        },
        
        pusher () {        
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;
    
            var _that = this
            var pusher = new Pusher('56423364aba2e84b5180', {
                cluster: 'us2'
            })
            var menuChannelPusher = pusher.subscribe(this.menuChannel);

            menuChannelPusher.bind('toPublicPanel', function(data) {
                if (data != null) {
                    _that.addShift(data)
                }
            })

            this.isActive = true
        },

        addShift (dataChannel) {
            var _that = this
            var shift_id = dataChannel.idTicket

            if (dataChannel.idUser == this.user) {
                            
                axios.post('get-shift-advisor', {
                    type: 2,
                    shiftId: shift_id
                })
                .then(function (response) {
                    _that.shiftList.push (
                        response.data[0]
                    )
                })
                .catch(function (error) {
                    console.log(error);
                })
            }
        },

        nextShift () {
            var _that = this

            if (this.shiftList.length > 0) {
                // console.log('No vacia')
                this.attending.id = this.shiftList[0].id

                axios.post('next-shift', {
                    'shiftId': _that.attending.id,
                    'panel_channel': _that.panelChannel
                })
                .then(function (response) {
                    _that.attending.shift = _that.shiftList[0].shift
                    _that.attending.speciality = _that.shiftList[0].speciality
                    _that.attending.type = _that.shiftList[0].shift_type
                    _that.attending.time = _that.shiftList[0].time.substring(11, 19)
                    _that.attending.client = _that.shiftList[0].name_client
                    _that.attending.number = _that.shiftList[0].number_client
                    _that.attending.sex = _that.shiftList[0].sex_client
    
                    _that.shiftList.splice(0, 1)
                })
                .catch(function (error) {
                    console.log(error);
                })               

            } else {
                $.notify({
                    title: "  ",
                    message: " No hay turnos por el momento.",
                    icon: 'fa fa-info-circle' 
                },{
                    newest_on_top: true,
                    type: "info"
                })
                this.setNotShiftAttending()
            }
        },

        changeStatusShift (status) {
            if (this.attending.id != 0) {
                console.log('No vacia')

                axios.post('status-shift', {
                    shiftId: this.attending.id,
                    typeStatus: status
                })
                .then(function (response) {
                    console.log(response.data)
                })
                .catch(function (error) {
                    console.log(error);
                })

            } else {
                $.notify({
                    title: "  ",
                    message: " Pase un turno para atenderlo.",
                    icon: 'fa fa-info-circle' 
                },{
                    newest_on_top: true,
                    type: "info"
                })
            }
        },

        setNotShiftAttending () {
            this.attending.shift = '-'
            this.attending.speciality = '-'
            this.attending.type = '-'
            this.attending.time = '-'
            this.attending.client = '-'
            this.attending.number = '-'
            this.attending.sex = '-'
        }
    }
})
