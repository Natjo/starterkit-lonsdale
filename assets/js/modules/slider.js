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
	const btn_next = slider.querySelector('.next');
	const btn_prev = slider.querySelector('.prev');
	let offsetX = 0;
	let index = 0;
	let itemW;
	let downX;
	let gap;
	let nb;

	// Use fakeScrollTo while smooth behavior not fully supported
	function fakeScrollTo(end) {
		let req;
		let init = null;
		let time;
		const start = content.scrollLeft;
		const duration = 500;
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
		content.classList.add('onswipe');
		content.scrollTo(-e.clientX + offsetX, 0);
	};

	const resize = () => {
		gap = parseInt(getComputedStyle(content).gridColumnGap);
		nb = parseInt(getComputedStyle(slider).getPropertyValue('--nb')) || 1;
		itemW = items[0].offsetWidth;
		goto(index);
	};

	const goto = num => {
		if (!isTouch) {
			fakeScrollTo((itemW + gap) * num);
		} else {
			content.scrollTo({
				left: (itemW + gap) * num,
				behavior: 'smooth'
			});
		}
	};

	const mouseUp = e => {
		index = 0;
		items.forEach((item, i) => {
			if (item.offsetLeft - (itemW / 2) - gap < content.scrollLeft) index = i;
		});

		goto(index);
		window.removeEventListener('mousemove', mouseMove);
		window.removeEventListener('mouseup', mouseUp);
		content.classList.remove('onswipe');
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
		if (index >= items.length - nb) index = items.length - nb;
		goto(index);
	};

	const prev = () => {
		index--;
		if (index <= 0) index = 0;
		goto(index);
	};

	if (btn_next) btn_next.onclick = () => next();
	if (btn_prev) btn_prev.onclick = () => prev();

	this.enable = () => {
		resize();
		if (!isTouch) {
			content.onmousedown = e => mouseDown(e.clientX);
			window.addEventListener('resize', resize, { passive: true });
		} else {
			content.classList.add('touchable');
		}
	};

	this.disable = () => {
		content.onmousedown = null;
		window.removeEventListener('resize', resize);
		mouseUp();
	};
}

export default Slider;