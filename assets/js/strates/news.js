/* eslint-disable */
import Slider from '../modules/slider.js';

export default (el) => {
    const slider = el.querySelector(".slider");
    const myslider = new Slider(slider);
    myslider.enable();
};
