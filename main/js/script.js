const swiper = new Swiper(".mySwiper", {
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
    });

function mostrarManutencao() {
      Swal.fire({
        title: 'Estamos em manutenção!',
        text: 'Agradeço ❤️',
        icon: 'info',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#5e2b2b',
        background: '#fffdfc',
        color: '#5e2b2b'
      });
    }