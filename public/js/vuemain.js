new Vue({
    el: '#vue',
    data() {
        return {
            text: 'Hello World!',
            items: [
                'thingie',
                'another thingie',
                'lots of stuff',
                'yadda yadda'
            ],
            message: 'This is a good place to type things.',
            counter: 0,
            count: 0

        }
    },
    methods: {
        increment() {
            this.counter++;
        },
        increment() {
            this.count++;
        },
        decrement() {
            this.count--;
        }
    }
});
