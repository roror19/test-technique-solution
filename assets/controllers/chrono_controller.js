import { Controller } from '@hotwired/stimulus';
const axios = require('axios');

let sec = '00';
let min = '00';
let hrs = '00';

export default class extends Controller {
    connect() {

        checkFile(this.element);

        $(this.element).on('click', function () {

            $(this).replaceWith('<p id="chrono" data-controller="chrono"><span id="hrs">' + hrs + '</span> : <span id="min">' + min + '</span> : <span id="sec">' + sec +'</span></p>')
            timer();
        })
    }
}

function tick(){
    sec = parseInt(sec) + 1;
    if (sec >= 60) {
        sec = '00';
        min = parseInt(min) + 1;
        if (min >= 60) {
            min = '00';
            min = parseInt(hrs) + 1;
        }
    }

    if (sec >= 1 && sec <= 9) {
        sec = '0' + sec
    }
    if (min >= 1 && min <= 9 && sec === '00') {
        min = '0' + min
    }
    if (hrs >= 1 && hrs <= 9 && sec === '00') {
        hrs = '0' + hrs
    }

    updateFile(sec, min, hrs)
}

function add() {
    tick();
    timer();
}

function timer() {
    setTimeout(add, 1000);

    $('#hrs').text(hrs)
    $('#min').text(min)
    $('#sec').text(sec)
}

function updateFile(sec, min, hrs)
{
    axios.post('/file-update', {
        sec: sec,
        min: min,
        hrs: hrs,
    }).then(r => {

    })
}

function checkFile(element)
{
    axios.get('/file-update-check').then(r => {
        if (r.data !== 'notStarted' && $(element).is('button')) {

            hrs = r.data.hrs;
            min = r.data.min;
            sec = r.data.sec;

            $(element).replaceWith('<p id="chrono" data-controller="chrono"><span id="hrs">' + hrs + '</span> : <span id="min">' + min + '</span> : <span id="sec">' + sec +'</span></p>')
            timer();
        }
    })
}
