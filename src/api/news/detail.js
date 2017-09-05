import Carousel from 'carousel-widget/carousel'
import './detail.css'

$(() => {

    if ($('#carousel .carousel-item').length>1) {
        new Carousel({
            targetSelector: '#carousel',
            itemSelector: '#carousel .carousel-item',
            indicatorSelector: '#carousel .carousel-indicators > li',
            indicatorCls: 'active'
        })
    }
})
