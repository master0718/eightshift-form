import Swiper from 'swiper';

export class CarouselSlider {
  constructor(options) {
    this.element = options.element;
    this.nextEl = options.nextEl;
    this.prevEl = options.prevEl;
  }

  init() {
    const item = this.element;

    const mySwiper = new Swiper(item, { // eslint-disable-line no-unused-vars
      loop: item.getAttribute('data-swiper-loop'),
      freeMode: item.getAttribute('data-swiper-freeMode'),
      slidesPerView: 'auto',
      spaceBetween: 25,
      keyboard: {
        enabled: true,
      },
      grabCursor: true,
      breakpointsInverse: true,
      navigation: {
        nextEl: this.nextEl,
        prevEl: this.prevEl,
      },
    });
  }
}
