Vue.component('item-shift', {
    props: [
        'id',
        'shift',
        'box',
    ],
    template: `
        <div class="row text-center my-1">
            <div class="col-12 line-head text-success"><h1>{{ shift }}</h1></div>
        </div>
    `,
})

var appTodo = new Vue({
    delimiters: ['${', '}'],
    el: '#app-public-display',
    data: {
        channel: null,
        attending:{
            shift: 'A001',
            box: '02'
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

            axios.get('list-shift', {
                client: this.ticketList
            })
            .then(function (response) {
                _that.shiftList = response.data['listShift']
                _that.channel = response.data['channel'].channel
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
            var pusher = new Pusher('56423364aba2e84b5180', {
                cluster: 'us2'
            })
            var channel = pusher.subscribe(this.channel);

            channel.bind('toPanel', function(data) {
                if (data != null) {
                    _that.addShift(data.text)
                }
            })
        },

        addShift (data) {
            var _that = this
            var shift_id = data

            if (shift_id != null) {
                            
                axios.post('get-shift', {
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

        atenddingShift () {

        }
    }
})
