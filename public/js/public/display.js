Vue.component('item-shift', {
    props: [
        'id',
        'shift',
        'box',
    ],
    template: `
        <div class="row text-center my-1">
            <div class="col-6 line-head text-success"><h1>{{ shift }}</h1></div>
            <div class="col-6 line-head text-muted"><h1>{{ box }}</h1></div>
        </div>
    `,
})

var appTodo = new Vue({
    delimiters: ['${', '}'],
    el: '#app-public-display',
    data: {
        attending:{
            shift: null,
            box: null
        },
        shiftList: {},
        hour: null
    },
    mounted: function(){
        this.getListTickets()
        this.date()
    },
    methods: {

        getListTickets () {
            var _that = this

            axios.get('/list-shift', {
                client: this.ticketList
            })
            .then(function (response) {
                console.log(response.data['listShift'])
                _that.shiftList = response.data['listShift']
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        date(){
            var d = new Date()
           
            this.hour = d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()
            
        }
    }
})
