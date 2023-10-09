/* eslint-disable */
import Slider from '../modules/slider.js';

export default (el) => {
    const sliders = document.querySelectorAll('.slider0,.slider1,.slider2,.slider3,.slider4');
    window.onload = () => {
        sliders.forEach(slider => {
            const myscroll = new Slider(slider);
            myscroll.enable();
        });
    }

    // slider 5 : disabled on desktop
    const slider5 = document.querySelector('.slider5');
    const myscroll = new Slider(slider5);
    myscroll.enable();
    let once = true;
    let ismobile = false;
    const resize = () => {
        ismobile = window.innerWidth > 768 ? false : true;
        if (ismobile != once) {
            ismobile ? myscroll.enable() : myscroll.disable();
        }
        once = ismobile;
    }
    window.addEventListener('resize', resize, { passive: true });
    resize();







    
    // trains
    const btns_add = el.querySelectorAll('.btn-add');
    const btns_reset = el.querySelectorAll('.btn-reset');
    const inputs = el.querySelectorAll('.strate-content input');

    const datas = {
        TER: {
            name: 'Avignon - Cavaillon',
            go: [
                {
                    depart: '10:41',
                    arriver: '11:23'
                },
                {
                    depart: '12:38',
                    arriver: '13:17'
                },
                {
                    depart: '14:44',
                    arriver: '15:25'
                },
                {
                    depart: '15:13',
                    arriver: '17:23'
                },
                {
                    depart: '15:41',
                    arriver: '17:23'
                },
                {
                    depart: '16:41',
                    arriver: '17:32'
                },
                {
                    depart: '17:10',
                    arriver: '18:32'
                },
                {
                    depart: '20:12',
                    arriver: '21:00'
                }
            ],
            back: [
                {
                    depart: '13:25',
                    arriver: '14:30'
                },
                {
                    depart: '16:15',
                    arriver: '17:30'
                }
            ]
        },
        915: {
            name: 'Avignon - Vignieres',
            go: [
                {
                    depart: '9:45',
                    arriver: '10:26'
                },
                {
                    depart: '15:55',
                    arriver: '16:36'
                },
                {
                    depart: '16:30',
                    arriver: '17:11'
                },
                {
                    depart: '18:20',
                    arriver: '19:01'
                }
            ],
            back: [
                {
                    depart: '8:48',
                    arriver: '9:35'
                },
                {
                    depart: '13:28',
                    arriver: '14:15'
                },
                {
                    depart: '14:03',
                    arriver: '14:50'
                },
                {
                    depart: '17:13',
                    arriver: '18:00'
                }
            ]
        }
    }

    function dateDiff(time1, titme2) {
        const date1 = new Date(`0001-01-01 ${time1}:00`);
        const date2 = new Date(`0001-01-01 ${titme2}:00`);
        const diff = {}
        let tmp = date2 - date1;
        tmp = Math.floor(tmp / 1000);
        diff.sec = tmp % 60;

        tmp = Math.floor((tmp - diff.sec) / 60);
        diff.min = tmp % 60;

        tmp = Math.floor((tmp - diff.min) / 60);
        diff.hour = tmp % 24;

        return `${diff.hour > 0 ? diff.hour + 'h' : ''}${Number(diff.min) > 10 ? '' + diff.min : "0" + diff.min}min`;
    }

    function dateDiff1(time1, titme2) {
        const date1 = new Date(`0001-01-01 ${time1}:00`);
        const date2 = new Date(`0001-01-01 ${titme2}:00`);
        const diff = {}
        let tmp = date2 - date1;
        tmp = Math.floor(tmp / 1000);
        diff.sec = tmp % 60;

        tmp = Math.floor((tmp - diff.sec) / 60);
        diff.min = tmp % 60;

        tmp = Math.floor((tmp - diff.min) / 60);
        diff.hour = tmp % 24;

        return Number(`${(diff.hour * 60) + diff.min}`);
    }

    const blur = (input) => {
        const hours = input.parentNode.querySelector('.hours').value;
        const minutes = input.parentNode.querySelector('.minutes').value;

        if (hours.length > 0 && minutes.length > 0) {
            const key = input.closest('form').dataset.type;
            let msg = "";
            for (let bus in datas) {
                let match = false;
                for (let time of datas[bus][key]) {
                    if (!match) {
                        let diff = dateDiff1(`${hours}:${minutes}`, time['depart']);
                        let correspondance = dateDiff(`${hours}:${minutes}`, time['depart']);
                        let classe = 'not';

                        if (diff <= 80 && diff > 40) {
                            match = true;
                            classe = 'large';
                        }
                        if (diff <= 40 && diff > 20) {
                            match = true;
                            classe = 'valid';
                        }
                        else if (diff <= 20 && diff > 15) {
                            match = true;
                            classe = 'risque';
                        }
                        else if (diff <= 15 && diff > 5) {
                            match = true;
                        }

                        if (match) {
                            msg += `<li class="${classe}"><b>${bus}</b> (${datas[bus]['name']}) ${time['depart']} - ${time['arriver']} (${correspondance} de correspondance)</li>`;
                        }
                    }
                }
            }

            input.parentNode.parentNode.querySelector('ul').innerHTML = msg ? msg : '<li>--</li>';
        }
    }


    const add = (inputs) => {
        inputs.forEach(input => {
            blur(input);
            input.onkeyup = (e) => {
                const charCode = e.keyCode;
                if (input.nextElementSibling && charCode != 9 && charCode != 16) {
                    if (input.value.length == 1 && charCode >= 99 && charCode <= 105) {
                        input.nextElementSibling.focus();
                    }

                    if (input.value.length == 2) {
                        input.nextElementSibling.focus();
                    }
                }

                // just numeric
                if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
            };

            input.onblur = (e) => {
                const charCode = e.keyCode;
                /*  if (input.value.length == 0 && input.classList.contains('minutes') && charCode != 98) {
                    input.value = '00';
                }
                if(input.value === '0' && input.classList.contains('minutes') && charCode != 98){
                    input.value = '00';
                }*/
                blur(input);
            }
        });
    }

    btns_add.forEach(btn => {
        btn.onclick = () => {
            const ol = btn.previousElementSibling;
            const template = document.querySelector("#time");
            const clone = document.importNode(template.content, true);
            ol.appendChild(clone);
            const inputs = ol.querySelectorAll('li:last-child input');

            add(inputs);
            inputs[inputs.length - 2].focus();
        }
    });

    btns_reset.forEach(btn => {
        btn.onclick = () => {
            const form = btn.closest('form');
            form.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            form.querySelectorAll('ul').forEach(ul => {
                ul.innerHTML = '';
            });
        }
    });

    add(inputs);
};
