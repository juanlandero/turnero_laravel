Vue.component('item-shift', {
    props: [
        'id',
        'shift',
        'box',
    ],
    template: `
        <div class="row text-center ">
            <div class="col-12 line-head text-info mt-2"><h1 class="mb-0">{{ shift }}</h1></div>
        </div>
    `,
})

var appDisplay = new Vue({
    delimiters: ['${', '}'],
    el: '#app-public-display',
    data: {
        audio: new Audio("/audio/chime.mp3"),   
        serviceOn: false,
        menuChannel: null,
        panelChannel: null,
        attending:{
            id: 0,
            shift: '-',
            box: '-'
        },
        shiftList: {},
        hour: null
    },
    created: function(){
        this.getListTickets()
        this.date()
    },
    methods: {

        getListTickets () {
            var _that = this

            axios.get('display/list')
            .then(function (response) {
                _that.shiftList = response.data['listShift']
                _that.menuChannel = response.data['channel'].menu_channel
                _that.panelChannel = response.data['channel'].panel_channel
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        date(){
            var d = new Date()
           
            this.hour = d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()
        },

        pusher () {        
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;
    
            var _that = this
            var pusher = new Pusher('edfdfd25a99f00107c87', {
                cluster: 'us2'
            })
            var menuChannelPusher = pusher.subscribe(this.menuChannel)
            var panelChannelPusher = pusher.subscribe(this.panelChannel)

            menuChannelPusher.bind('newShift', function(data) {
                if (data != null) {
                    _that.addShift(data.idTicket)
                }
            })

            panelChannelPusher.bind('nextShift', function(data) {
                _that.atenddingShift (data.idTicket, data.box)
                _that.audio.play()
            })

            this.serviceOn = true
        },

        addShift (data) {
            var _that = this
            var shift_id = data

            if (shift_id != null) {
                            
                axios.post('display/get', {
                    shiftId: shift_id
                })
                .then(function (response) {
                    _that.shiftList.push (
                        response.data['shift']
                    )
                    console.log(response)
                })
                .catch(function (error) {
                    console.log(error);
                })
            }
            
        },

        atenddingShift (shiftId, box) {
            var _that = this
            if (this.shiftList.length > 0) {

                this.shiftList.forEach(function (shift, index, arr) {
                    if (shift.id == shiftId) {
                        _that.attending.id = shift.id
                        _that.attending.shift = shift.shift
                        _that.attending.box = box

                        _that.shiftList.splice(index, 1)
                    } 
                })
                
            } else {
                this.setNotShiftAttending()
            }
        },

        setNotShiftAttending () {
            _that.attending.id = 0
            _that.attending.shift = '-'
            _that.attending.box = '-'
        },
    }
})
