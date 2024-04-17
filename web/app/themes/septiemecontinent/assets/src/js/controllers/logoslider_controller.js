import { Controller } from "@hotwired/stimulus";
import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

// CSS
import 'swiper/css';
import 'swiper/css/navigation';

export default class extends Controller {
    static afterLoad(identifier, application) {
        const swiper = new Swiper(document.querySelector('.swiper', identifier), {
            modules: [Navigation],
            loop: true,
            spaceBetween: 20,
            slidesPerView: 4,
            navigation: {
                nextEl: document.querySelector('.swiper-button-next', identifier),
                prevEl: document.querySelector('.swiper-button-prev', identifier)
            }
        });
    }
}
