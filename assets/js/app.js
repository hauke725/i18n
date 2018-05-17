import Vue from 'vue';

let app = new Vue({
    el: '#app',
    data: {
        message: 'Test!?',
        message2: 'Okay!'
    },
    delimiters: ['${', '}']
});