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
    const inputs = el.querySelectorAll('input');

    const datas = {
        906: {
            name: 'Avignon - Ile sur la sorgue',
            go: [
                {
                    depart: '9:40',
                    arriver: '10:30'
                },
                {
                    depart: '17:20',
                    arriver: '18:30'
                }
            ],
            back: [
                {
                    depart: '9:15',
                    arriver: '10:30'

                },
                {
                    depart: '16:30',
                    arriver: '18:30'
                }
            ]
        },
        907: {
            name: 'Avignon - Cavaillon',
            go: [
                {
                    depart: '12:20',
                    arriver: '13:30'
                },
                {
                    depart: '17:15',
                    arriver: '18:30'
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
                    arriver: '10:45'
                },
                {
                    depart: '15:55',
                    arriver: '16:55'
                },
                {
                    depart: '16:30',
                    arriver: '17:30'
                },
                {
                    depart: '18:20',
                    arriver: '19:30'
                }
            ],
            back: [
                {
                    depart: '8:48',
                    arriver: '9:45'
                },
                {
                    depart: '13:28',
                    arriver: '14:55'
                },
                {
                    depart: '14:03',
                    arriver: '15:30'
                },
                {
                    depart: '17:13',
                    arriver: '18:30'
                }
            ]
        }
    }

    function round(val) {
        // return Math.round(val * 100) / 100
        const ff = String(Math.round(val * 100) / 100).split('.');
        
        return ff[1].length == 1 ? (ff[1] + 0) : ff[1];
    }

    const blur = (input) => {
        const hours = input.parentNode.querySelector('.hours').value;
        const minutes = input.parentNode.querySelector('.minutes').value;
        const value = Number(hours) + Number(minutes / 100);

        if (hours.length > 0 && minutes.length > 0) {

            const key = input.name;
            let msg = "";
            for (let bus in datas) {
                let match = false;
                for (let time of datas[bus][key]) {
                    const depart = Number(time['depart'].replace(':', '.'));
                    if (!match) {
                        let diff = depart - value;
                        let status = 0;

                        if (diff < .4 && diff > .2) {
                            status = 1;
                            match = true;
                            msg += `<li class="valid"><b>${bus}</b> (${datas[bus]['name']}) ${time['depart']} - ${time['arriver']} (${round(diff)}minutes)</li>`;
                        }
                        else if (diff <= .2 && diff >= .15) {
                            status = 2;
                            match = true;
                            msg += `<li class="risque"><b>${bus}</b> (${datas[bus]['name']}) ${time['depart']} - ${time['arriver']} (${round(diff)}minutes)</li>`;
                        }
                        else {

                        }
                    }
                }
            }

            input.parentNode.parentNode.querySelector('ul').innerHTML = msg ? msg : '<li class="not">Pas de correspondances</li>';
        } else {

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