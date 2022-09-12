/* eslint-disable */
/**
 *  @module Rgpd
 *  @description
 *  Managing cookies via categories.
 *
 *  @element .rgpd-manage-link - open the manage popin
 *
 *  @callback onexec(cat) - fired if at least 1 category is enabled.
 *  @param cat - in checkbox val, is the id of the cookie ex 'stats'
 *  @var cookieName name of the rgpd cookie
 *
 */

 const Rgpd = onexec => {
    const cookieName = 'rgpd';
    const root = document.documentElement || window;
    const clicktouch = ('ontouchstart' in root) ? 'touchstart' : 'click';
    const modal = document.getElementById('rgpd-modal');
    const btn_accept = modal.querySelector('.btn-accept');
    const btn_refuse = modal.querySelector('.btn-refuse');
    const manage = document.getElementById('rgpd-manage');
    const manage_box = manage.querySelector('.box');
    const btn_save = manage.querySelector('.btn-save');
    const checkboxes = manage.querySelectorAll('input[type="checkbox"]');
    const btn_close = manage.querySelector('.btn-close');
    const manage_button = document.querySelectorAll('.rgpd-manage-link');
    const manage_links = document.querySelectorAll('a[href="#rgpd-manage"]');
    let scrollTop;
    let cats = {};
    modal.removeAttribute('style');
    manage.removeAttribute('style');

    const eraseUnusedCookies = () => {
        for (const checkbox of checkboxes) {
            if(!checkbox.checked){
                checkbox.dataset.cookies.split(',').forEach(name => {
                    let hostname = document.location.hostname;
                    if (hostname.indexOf('www.') === 0) hostname = hostname.substring(4);
                    document.cookie = `${name}=; domain=.${hostname}; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=`;
                    document.cookie = `${name}=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/`;
                });
            }
        }
    };

    let consent = localStorage.getItem(cookieName) ? true : false;

    // fire event onexec if cat == true
    const execute = () => {
        for (const key in cats) (cats[key] && typeof onexec === 'function') && onexec(key);
    };

    // set cookie obj status
    const status = () => checkboxes.forEach(item => {
        cats[item.value] = item.checked ? true : false;
    });

    const set_consent = () => {
        if(!consent){
            const nonce = manage.getAttribute('data-nonce');
            const action = manage.getAttribute('data-action');
            const formData = new window.FormData();
            formData.append('nonce', nonce);
            formData.append('action', action);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', ParamsData.wp_ajax_url);
            xhr.send(formData);
            document.activeElement.blur();
        }else{
            trap.btn.focus();
        }
        consent = true;
        modal.setAttribute('aria-hidden', true);
        localStorage.setItem(cookieName, JSON.stringify(cats));
    };

    const accept = () => {
        status();
        execute();
        set_consent();
    };

    const denie = () => {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        status();
        set_consent();
    };

    const save = () => {
        status();
        !consent && execute();
        set_consent();
        eraseUnusedCookies();
    };

    const disableScroll = () => window.scrollTo(0, scrollTop);

    // init or load cookie obj
    if (consent === true) cats = JSON.parse(localStorage.getItem(cookieName));

    checkboxes.forEach(checkbox => {
        cats[checkbox.value] = true;
    });
  
    // init checkboxes
    for (let key in cats) {
        for (let checkbox of checkboxes) {
            if (checkbox.value === key) checkbox.checked = cats[key];
        }
    }

    btn_accept.onclick = () => accept();

    btn_refuse.onclick = () => denie();

    if (consent === true) {
        execute();
    } else {
        modal.removeAttribute('aria-hidden');
        btn_refuse.focus();
    }

    const clickoutside = e => !manage_box.contains(e.target) && close();

    const trap = {
        index: 0,
        els: [],
        isShifted: false,
        init() {
            manage.querySelectorAll('button,a,input,summary').forEach(el => trap.els.push(el));
        },
        keyup(e) {
            e.key === 'Escape' && close();
            if (e.key === 'Shift') {
                trap.isShifted = false;
            }
        },
        keydown(e) {
            if (e.key === 'Shift') {
                trap.isShifted = true;
            }
            if (e.key === 'Tab') {
                if (e.preventDefault) e.preventDefault();
                else e.returnValue = false;
                trap.isShifted ? trap.index-- : trap.index++;
                if (trap.index < 0) trap.index = trap.els.length - 1;
                if (trap.index >= trap.els.length) trap.index = 0;
                trap.els[trap.index].focus();
            }
        },
        add() {
            btn_close.focus();
            document.addEventListener('keydown', trap.keydown, false);
            document.addEventListener('keyup', trap.keyup, false);
        },
        remove() {
            document.removeEventListener('keydown', trap.keydown);
            document.removeEventListener('keyup', trap.keyup);
        },
    };

    trap.init();

    const open = () => {
        trap.btn = document.activeElement;
        status();
        modal.setAttribute('aria-hidden', true);
        manage.classList.add('open');
        manage.addEventListener('animationend', () => {
            window.addEventListener(clicktouch, clickoutside, { once: true });
        }, { once: true });
        scrollTop = window.pageYOffset || window.scrollY;
        window.addEventListener('scroll', disableScroll);
        trap.add();
        trap.btn.setAttribute('aria-expanded', true);
        document.querySelector('body').classList.add('no-scroll');
    };

    const close = () => {
        if (!consent) {
            modal.removeAttribute('aria-hidden');
        }
        manage.classList.add('close');
        window.removeEventListener(clicktouch, clickoutside);
        window.removeEventListener('scroll', disableScroll);

        manage.addEventListener('animationend', () => {
            manage.classList.remove('open');
            manage.classList.remove('close');
        }, { once: true });
        trap.remove();
        trap.btn.focus();
        trap.btn.setAttribute('aria-expanded', false);
        document.querySelector('body').classList.remove('no-scroll');
    };

    manage_links.forEach(link => {
        link.onclick = e => {
            e.stopPropagation();
            e.preventDefault();
            open();
        };
    });
    manage_button.forEach(link => {
        link.onclick = e => {
            e.stopPropagation();
            e.preventDefault();
            open();
        };
    });

    btn_save.onclick = () => {
        save();
        close();
    };

    btn_close.onclick = e => close(e);
};

export default Rgpd;
