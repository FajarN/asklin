<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Rakernas ASKLIN</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/frontend/rakernas/assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/frontend/rakernas/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/frontend/rakernas/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >
  <link href="{{ asset('assets/frontend/rakernas/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" >
  <link href="{{ asset('assets/frontend/rakernas/assets/vendor/aos/aos.css') }}" rel="stylesheet" >
  <link href="{{ asset('assets/frontend/rakernas/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet" >
  <link href="{{ asset('assets/frontend/rakernas/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet" >

  <!-- Main CSS File -->
  <link href="{{ asset('assets/frontend/rakernas/assets/vendor/main.css') }}" rel="stylesheet" >


</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="https://asklin.org/" class="logo d-flex align-items-center me-auto">
        <img src="{{ asset('assets/frontend/rakernas/assets/img/logo.png') }}" alt="">
        <!-- Uncomment the line below if you also wish to use an text logo -->
        <!-- <h1 class="sitename">TheEvent</h1>  -->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="https://asklin.org/" class="active">Beranda<br></a></li>
          <li><a href="#speakers">Pembicara</a></li>
          <li><a href="#schedule">Rundown</a></li>
          <li><a href="#venue">Hotel</a></li>
          <li><a href="#hotels">Destinasi</a></li>
          <li><a href="#gallery">Galeri</a></li>
          <li><a href="#contact">Kontak</a></li>
          <li><a href="#buy-tickets">Pendaftaran</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="cta-btn d-none d-sm-block" href="#buy-tickets">Pendaftaran</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="{{ asset('assets/frontend/rakernas/assets/img/hero-bg.jpg') }}" alt="" data-aos="fade-in" class="">

      <div class="container d-flex flex-column align-items-center text-center mt-auto">
        <div data-aos="fade-up" data-aos-delay="300" class="">
          <a href="https://youtu.be/FP3NNxkfeoM?si=9tHwR0qCjEbxN2FL" class="glightbox pulsating-play-btn mt-3"></a>
        </div>
      </div>

      <div class="about-info mt-auto position-relative">

        
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- Speakers Section -->
    <section id="speakers" class="speakers section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Pembicara<br></h2>

      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member">
              <img src="{{ asset('assets/frontend/rakernas/assets/img/speakers/speaker-1.jpg') }}" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4><a href="speaker-details.html">dr. Eddi Junaidi, Sp.O.G.S.H, M.Kes</a></h4>
                  <span>Ketua Umum ASKLIN</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->
          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <img src="{{ asset('assets/frontend/rakernas/assets/img/speakers/speaker-2.jpg') }}" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                 <h4><a href="speaker-details.html">dr. Hasan Amin, Sp.Rad.</a></h4>
                  <span>Ketua Panitia Pelaksana Rakernas Asklin 2024</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <img src="{{ asset('assets/frontend/rakernas/assets/img/speakers/speaker-3.jpg') }}" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
              <h4><a href="speaker-details.html">Dr. dr Mahlil Ruby, M.Kes</a></h4>
                  <span>Direktur Perencanaan Pengembangan dan Manajmen Resiko</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="member">
              <img src="{{ asset('assets/frontend/rakernas/assets/img/speakers/speaker-4.jpg') }}" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                <h4><a href="speaker-details.html">dr. Beno Herman, MARS, AAK</a></h4>
                  <span>Deputi Direksi Bidang Manajemen Mutu dan Faskes Kesehatan BPJS Kesehatan</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="member">
              <img src="{{ asset('assets/frontend/rakernas/assets/img/speakers/speaker-5.jpg') }}" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                <h4><a href="speaker-details.html">Kementrian Kesehatan</a><br><span>* Dalam Konfirmasi</span></h4>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="member">
              <img src="{{ asset('assets/frontend/rakernas/assets/img/speakers/speaker-5.jpg') }}" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                <h4><a href="speaker-details.html">PJ Gubernur NTB</a><br><span>* Dalam Konfirmasi</span></h4>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- /Speakers Section -->

    <!-- Schedule Section -->
    <section id="schedule" class="schedule section">

      <!-- Section Title -->
      
       
          </div><!-- End Schdule Day 3 -->

        </div>

      </div>
    </section><!-- /Schedule Section -->

    <!-- Venue Section -->
    <section id="venue" class="venue section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Venue Hotel<br></h2>
        <p>Jl. Sriwijaya No.132, Cilinaya, Kec. Cakranegara, Kota Mataram, Nusa Tenggara Bar. 83232</p>
      </div><!-- End Section Title -->

      <div class="container-fluid" data-aos="fade-up">

        <div class="row g-0">
          <div class="col-lg-6 venue-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15780.113427030832!2d116.1189636!3d-8.5932718!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcdbf5b92f294e3%3A0x9f502f155e07deed!2sPuri%20Indah%20Hotel%20%26%20Conventions!5e0!3m2!1sid!2sid!4v1725292541473!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>          </div>

          <div class="col-lg-6 venue-info">
            <div class="row justify-content-center">
              <div class="col-11 col-lg-8 position-relative">
                <h3>Puri Indah Hotel & Conventions</h3>
                <p></p>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="container-fluid venue-gallery-container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="venue-gallery">
              <a href="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-1.jpg') }}" class="glightbox" data-gall="venue-gallery">
                <img src="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-1.jpg') }}" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="venue-gallery">
              <a href="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-2.jpg') }}" class="glightbox" data-gall="venue-gallery">
                <img src="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-2.jpg') }}" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="venue-gallery">
              <a href="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-3.jpg') }}" class="glightbox" data-gall="venue-gallery">
                <img src="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-3.jpg') }}" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="venue-gallery">
              <a href="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-4.jpg') }}" class="glightbox" data-gall="venue-gallery">
                <img src="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-4.jpg') }}" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="venue-gallery">
              <a href="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-5.jpg') }}" class="glightbox" data-gall="venue-gallery">
                <img src="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-5.jpg') }}" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="venue-gallery">
              <a href="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-6.jpg') }}" class="glightbox" data-gall="venue-gallery">
                <img src="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-6.jpg') }}" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="venue-gallery">
              <a href="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-7.jpg') }}" class="glightbox" data-gall="venue-gallery">
                <img src="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-7.jpg') }}" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="venue-gallery">
              <a href="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-8.jpg') }}" class="glightbox" data-gall="venue-gallery">
                <img src="{{ asset('assets/frontend/rakernas/assets/img/venue-gallery/venue-gallery-8.jpg') }}" alt="" class="img-fluid">
              </a>
            </div>
          </div>

        </div>
      </div>

    </section><!-- /Venue Section -->

    <!-- Hotels Section -->
    <section id="hotels" class="hotels section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Destinasi Wisata</h2>
        <p>Peserta RAKERNAS akan dimanjakan dengan berbagai keindahan wisata setelah mengikuti kegiatan</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100">
              <iframe width="340" height="100%" src="https://www.youtube.com/embed/23lFhRHtbsQ?si=aXd7RlnZpRzg3qlh" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
              <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
              <p>Destinasi Senggigi</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100">
<iframe width="340" height="100%" src="https://www.youtube.com/embed/I2fUIJ79yvs?si=9aArBoWvdTROoayx" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
              <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
              <p>Gili Trawangan</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100">
              <iframe width="100%" height="340" src="https://www.youtube.com/embed/Wq3cokM0VsU?si=zGBGWblSkf3K7Jtt" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
              <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
              <p>Sirkuit Mandalika </p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100">
              <iframe width="100%" height="340" src="https://www.youtube.com/embed/48XFizUICA4?si=-BDoLTqirvu46jB4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
              <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
              <p>Bukit Merese</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100">
            <iframe width="100%" height="340" src="https://www.youtube.com/embed/GmimaC37NS4?si=j9zoEVBQxylooR-0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
              <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
              <p>Pantai Kuta Mandalika </p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100">
              <iframe width="100%" height="340" src="https://www.youtube.com/embed/weovzBVODa8?si=WMtz9Dt5A0zRDcnk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
              <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
              <p>Tenun Sakurara</p>
            </div>
          </div><!-- End Card Item -->

        </div>

      </div>

    </section><!-- /Hotels Section -->

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>View dalam hotel</h2>
        <p>Berikut adalah beberapa foto-foto Puri Indah Hotel</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "centeredSlides": true,
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 0
                },
                "768": {
                  "slidesPerView": 3,
                  "spaceBetween": 20
                },
                "1200": {
                  "slidesPerView": 5,
                  "spaceBetween": 20
                }
              }
            }
          </script>
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-1.jpg') }}"><img src="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-1.jpg') }}" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-2.jpg') }}"><img src="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-2.jpg') }}" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-3.jpg') }}"><img src="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-3.jpg') }}" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-4.jpg') }}"><img src="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-4.jpg') }}" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-5.jpg') }}"><img src="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-5.jpg') }}" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-6.jpg') }}"><img src="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-6.jpg') }}" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-7.jpg') }}"><img src="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-7.jpg') }}" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-8.jpg') }}"><img src="{{ asset('assets/frontend/rakernas/assets/img/event-gallery/event-gallery-8.jpg') }}" class="img-fluid" alt=""></a></div>
          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Gallery Section -->

    <!-- Sponsors Section -->
    <section id="sponsors" class="sponsors section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Patners</h2>
        <p>Terima kasih kepada para sponsor & Patners yang telah membersamai kami dalam kegiatan RAKERNAS</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0 clients-wrap">

          <div class="col-xl-3 col-md-4 client-logo">
            <a href="https://mestikaaplikasi.com/" target="_blank">
              <img src="{{ asset('assets/frontend/rakernas/assets/img/clients/client-1.png') }}" class="img-fluid" alt=""></a>
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('assets/frontend/rakernas/assets/img/clients/client-2.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('assets/frontend/rakernas/assets/img/clients/client-3.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('assets/frontend/rakernas/assets/img/clients/client-4.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('assets/frontend/rakernas/assets/img/clients/client-5.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('assets/frontend/rakernas/assets/img/clients/client-6.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('assets/frontend/rakernas/assets/img/clients/client-7.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('assets/frontend/rakernas/assets/img/clients/client-8.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

        </div>

      </div>

    </section><!-- /Sponsors Section -->

    <!-- Faq Section -->
      
              </div><!-- End Faq item-->

            </div>

          </div><!-- End Faq Column-->

        </div>

      </div>

    </section><!-- /Faq Section -->

    <!-- Buy Tickets Section -->
    <section id="buy-tickets" class="buy-tickets section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Pendaftaran RAKERNAS<br></h2>
        <p>Disini peserta akan kami jelaskan diantaranya ada beberapa ketentuan baiaya di luar tiket pesat</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4 pricing-item" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <h3>Paket A</h3>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <h4><sup>Rp</sup>1.700.000<span></span></h4>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <ul>
			 <li><i class="bi bi-check"></i> <span>RAKERNAS</span></li>
              <li><i class="bi bi-check"></i> <span>Hari Pertama</span></li>
              <li><i class="bi bi-check"></i> <span>Hari Kedua</span></li>
              
            </ul>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <div class="text-center"><a href="https://docs.google.com/forms/d/e/1FAIpQLSd49hQ7Rwb1MutX11k6ripmrrA9767v6b_MlIShBz-dbOix_Q/viewform" class="buy-btn">Buy Now</a></div>
          </div>
        </div><!-- End Pricing Item -->

        <div class="row gy-4 pricing-item featured mt-4" data-aos="fade-up" data-aos-delay="200">
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <h3>Paket B<br></h3>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <h4><sup>Rp</sup>500.000<span></span></h4>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <ul>
              <li><i class="bi bi-check"></i> <span>RAKERNAS</span></li>
            <li><i class="bi bi-check"></i> <span>Hari Pertama</span></li>
              <li><i class="bi bi-check"></i> <span>Hari Kedua</span></li>
            </ul>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <div class="text-center"><a href="https://docs.google.com/forms/d/e/1FAIpQLSd49hQ7Rwb1MutX11k6ripmrrA9767v6b_MlIShBz-dbOix_Q/viewform" class="buy-btn">Buy Now</a></div>
          </div>
        </div><!-- End Pricing Item -->

        <div class="row gy-4 pricing-item mt-4" data-aos="fade-up" data-aos-delay="300">
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <h3>Paket A+B<br></h3>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <h4><sup>Rp</sup>2.200.000<span></span></h4>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <ul>
              <li><i class="bi bi-check"></i> <span>RAKERNAS</span></li>
              <li><i class="bi bi-check"></i> <span>Hari Pertama</span></li>
              <li><i class="bi bi-check"></i> <span>Hari Kedua</span></li>
            </ul>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <div class="text-center"><a href="https://docs.google.com/forms/d/e/1FAIpQLSd49hQ7Rwb1MutX11k6ripmrrA9767v6b_MlIShBz-dbOix_Q/viewform" class="buy-btn">Buy Now</a></div>
          </div>
        </div><!-- End Pricing Item -->

      </div>

    </section><!-- /Buy Tickets Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Informasi</h2>
        <p>Kami menyediakan pusat informasi untuk para peserta dalam menanyakan kegiatan dalam RAKERNAS</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt"></i>
              <h3>Sekretariat RAKERNAS</h3>
              <p>Jl. Sriwijaya No.132, Cilinaya, Kec. Cakranegara, Kota Mataram, Nusa Tenggara Barat</p>
            </div>
          </div><!-- End Info Item -->

          <div class="col-lg-3 col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone"></i>
              <h3>Call Us</h3>
              <p>Hasim : <a href="https://api.whatsapp.com/send/?phone=6282310981637"target="_blank">082310981637</a></p>
              <p>Bu Erna :   <a href="https://api.whatsapp.com/send/?phone=628129925457"target="_blank">08129925457</a> </p>
              <p>dr Dimas : <a href="https://api.whatsapp.com/send/?phone=6282339114469"target="_blank">6282339114469</a> </p>
            </div>
          </div><!-- End Info Item -->

          <div class="col-lg-3 col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope"></i>
              <h3>Email</h3>
              <p>asklinppid@gmail.com</p>
            </div>
          </div><!-- End Info Item -->

        </div>

        <div class="row gy-4 mt-1">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15780.113427030832!2d116.1189636!3d-8.5932718!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcdbf5b92f294e3%3A0x9f502f155e07deed!2sPuri%20Indah%20Hotel%20%26%20Conventions!5e0!3m2!1sid!2sid!4v1725292541473!5m2!1sid!2sid" frameborder="0" style="border:0; width: 100%; height: 400px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div><!-- End Google Maps -->

          <div class="col-lg-6">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="400">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Send Message</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    

    <div class="copyright text-center">
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>
            © Copyright <strong><span>Kominfo ASKLIN</span></strong>. All Rights Reserved
          </div>
          
          </div>
        </div>

        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
          <a href="https://x.com/asklinpp/"><i class="bi bi-twitter-x"></i></a>
          <a href="https://www.facebook.com/asklinpp/"><i class="bi bi-facebook"></i></a>
          <a href="https://www.instagram.com/asklinpp/"><i class="bi bi-instagram"></i></a>
          <a href="https://www.linkedin.com/asklinpp"><i class="bi bi-linkedin"></i></a>
        </div>

      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  {{-- <div id="preloader"></div> --}}

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/frontend/rakernas/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/rakernas/assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/frontend/rakernas/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/frontend/rakernas/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/rakernas/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/frontend/rakernas/assets/js/main.js') }}"></script>

</body>

</html>