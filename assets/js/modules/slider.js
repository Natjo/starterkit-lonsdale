/* eslint-disable */
/**
 * @module Slider
 * @param {HTMLElement} el 
 * 
 */

// Change animation by scrollIntoView when supported
function Slider(slider) {
    const isTouch = 'ontouchstart' in document.documentElement;
    const content = slider.querySelector('.slider-content');
    const items = content.querySelectorAll('.item');
    const pagination = slider.querySelector('.slider-pagination');
    const btn_next = slider.querySelector('.next');
    const btn_prev = slider.querySelector('.prev');
    const focusEls = slider.querySelectorAll('.item a,.item button,.item input');
    let bullets = [];
    let offsetX = 0;
    let index = 0;
    let downX;
    let length = 1;
    let paddingLeft;
    let isMove = false;
    let number;
    
    const getLength = () => {
        length = 0;
        for (let i = 0; i < items.length; i++) {
            if (items[i].offsetLeft < content.scrollWidth - content.offsetWidth + paddingLeft) {
                length++;
            }
        }
    }

    getLength();

    focusEls.forEach(el => {
        el.onfocus = () => {
            const item = el.closest('.item');
            index = [...item.parentNode.children].indexOf(item);
            if (index > length) index = length;
            goto();
        }
    });

    if (pagination) {
        for (let i = 0; i < items.length; i++) {
            const bullet = document.createElement('button');
            bullet.value = i;
            bullet.setAttribute('aria-hidden', true);
            bullet.setAttribute('tabindex', -1);
            pagination.appendChild(bullet);
            bullets.push(bullet);
        }
    }

    function fakeScrollTo(end) {
        let req;
        let init = null;
        let time;
        const start = content.scrollLeft;
        const duration = 600;
        const easing = (t, b, c, d) => -c * ((t = t / d - 1) * t * t * t - 1) + b;
        const startAnim = timeStamp => {
            init = timeStamp;
            draw(timeStamp);
        }
        const draw = now => {
            time = now - init;
            content.scrollTo(easing(time, start, end - start, duration), 0);
            req = window.requestAnimationFrame(draw);
            time >= duration && window.cancelAnimationFrame(req);
        }
        req = window.requestAnimationFrame(startAnim)
    };

    const mouseMove = e => {
        !isMove && content.classList.add('onswipe');
        content.scrollTo(-e.clientX + offsetX, 0);
        isMove = true;
    };

    const resize = () => {
        slider.style.setProperty('--ctr-left', `${slider.getBoundingClientRect().left}px`);
        slider.style.setProperty('--ctr-width', `${slider.offsetWidth}px`);
        paddingLeft = slider.offsetLeft + parseInt(getComputedStyle(slider).getPropertyValue('--padding-left') * 10);
        number = 1 + parseInt(getComputedStyle(slider).getPropertyValue('--nb'));
        getLength();
        goto();
    };

    function controlStatus() {
        if (pagination) {
            for (let i = 0; i < bullets.length; i++) {
                bullets[i].classList[i === index ? 'add' : 'remove']('active');
                bullets[i].classList[i <= length ? 'remove' : 'add']('hide')
            }
            if (bullets.length == 1) bullets[0].classList.add('hide')
        }
        if (btn_prev) {
            if (index <= 0) btn_prev.setAttribute('aria-disabled', true);
            else btn_prev.removeAttribute('aria-disabled');
            btn_prev.classList[length === 0 ? 'add' : 'remove']('hide');
        }
        if (btn_next) {
            if (index == length) btn_next.setAttribute('aria-disabled', true);
            else btn_next.removeAttribute('aria-disabled');
            btn_next.classList[length === 0 ? 'add' : 'remove']('hide');
        }
    }

    const goto = () => {
        controlStatus();
        const itempos = items[index].offsetLeft - paddingLeft;
        let diff = itempos - (content.scrollWidth - content.offsetWidth);
        if (diff < 0) diff = 0;
        if (!isTouch) {
            fakeScrollTo(itempos - diff);
        } else {
            content.scrollTo({
                left: itempos - diff,
                behavior: 'smooth'
            });
        }
    };

    function getIndex() {
        index = -1;
        items.forEach(item => {
            if (item.offsetLeft < content.scrollLeft + content.offsetWidth / number) index = index + 1;
        });
        if (offsetX - downX > content.scrollLeft) index = index - 1;
        if (index <= 0) index = 0;
        if (index > length) index = length;
    }

    const mouseUp = e => {
        window.removeEventListener('mousemove', mouseMove);
        window.removeEventListener('mouseup', mouseUp);
        content.classList.remove('onswipe');
        if (!isMove) return;
        getIndex();
        goto();
        isMove = false;
    };

    const mouseDown = val => {
        downX = val;
        offsetX = downX + content.scrollLeft;
        window.addEventListener('mousemove', mouseMove);
        window.addEventListener('mouseup', mouseUp);
        return false;
    };

    const next = () => {
        index++;
        if (index >= length) index = length;
        goto();
    };

    const prev = () => {
        index--;
        if (index <= 0) index = 0;
        goto();
    };

    if (btn_next) btn_next.onclick = () => next();
    if (btn_prev) btn_prev.onclick = () => prev();

    bullets.forEach(bullet => {
        bullet.onclick = () => {
            index = Number(bullet.value);
            goto();
        }
    })

    this.enable = () => {
        slider.classList.add('slider');
        index = 0;
        resize();
        if (!isTouch) {
            content.onmousedown = e => mouseDown(e.clientX);
            window.addEventListener('resize', resize, { passive: true });
        } else {
            content.classList.add('touchable');

            content.addEventListener('scroll', () => {
                getIndex();
                controlStatus();
            }, { passive: true });
        }
    };

    this.disable = () => {
        slider.classList.remove('slider');
        content.onmousedown = null;
        window.removeEventListener('resize', resize);
        mouseUp();
    };
}

export default Slider;