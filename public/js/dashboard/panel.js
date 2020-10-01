Vue.component('item-my-list', {
    props: [
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

var appTodo = new Vue({
    delimiters: ['${', '}'],
    el: '#app-panel-tickets',
    data: {
        isActive: false,
        channel: null,
        user: null,
        attending:{
            shift: 'A001',
            speciality: 'Frenos',
            type: 'Premium',
            sex: 'M',
            client: 'Juan Carlos Landero Isidro',
            number: '0987',
            box: '02'
        },
        shiftList: []
    },
    created: function(){
        
    },
    methods: {

        setServiceOn () {
            var _that = this

            axios.post('get-user')
            .then(function (response) {

                if (response.data['channel'] == null) {
                    alert('Necesita ser vinculado con una oficina')
                }else{
                    _that.channel = response.data['channel']
                    _that.user = response.data['idUser']
                    _that.pusher()
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
            var channel = pusher.subscribe(this.channel);

            channel.bind('toPanel', function(data) {
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
                    shiftId: shift_id
                })
                .then(function (response) {
                    console.log(response.data['shift'])
                    _that.shiftList.push (
                        response.data['shift']
                    )
                })
                .catch(function (error) {
                    console.log(error);
                })
            }
            
        }
    }
})
