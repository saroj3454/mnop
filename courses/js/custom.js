
$('.carousel-main').owlCarousel({
  items: 3,
  // loop: true,
  autoplay: false,
  autoplayTimeout: 1500,
  nav: true,
  dots: false,
  navText: ['<span class="fas fa-chevron-left fa-3x"></span>','<span class="fas fa-chevron-right fa-3x"></span>'],
  interval: 5000,
  wrap: false,
  margin:10,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:1,
            nav:false
        },
        1000:{
            items:3,
            nav:true,
            loop:false
        }
    }
})