<?php include("./server/connection.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sitename ?> || Mobile Services, Credit Cards, Mortgages, Auto Loan</title>
    <!-- Favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets_h/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/IMG_09099.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="images/IMG_09099.png"" />
    <link rel=" manifest" href="images/IMG_09099.png" />
    <meta name="description" content="<?php echo $sitename ?> Bank HTML 5 Template " />
    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets_h/vendors/animate/animate.min.css" />
    <link rel="stylesheet" href="assets_h/vendors/animate/custom-animate.css" />
    <link rel="stylesheet" href="assets_h/vendors/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets_h/vendors/bxslider/jquery.bxslider.css" />
    <link rel="stylesheet" href="assets_h/vendors/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets_h/vendors/jquery-magnific-popup/jquery.magnific-popup.css" />
    <link rel="stylesheet" href="assets_h/vendors/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="assets_h/vendors/nice-select/nice-select.css" />
    <link rel="stylesheet" href="assets_h/vendors/odometer/odometer.min.css" />
    <link rel="stylesheet" href="assets_h/vendors/owl-carousel/owl.carousel.min.css" />
    <link rel="stylesheet" href="assets_h/vendors/owl-carousel/owl.theme.default.min.css" />
    <link rel="stylesheet" href="assets_h/vendors/swiper/swiper.min.css" />
    <link rel="stylesheet" href="assets_h/vendors/vegas/vegas.min.css" />
    <link rel="stylesheet" href="assets_h/vendors/thm-icons/style.css">
    <link rel="stylesheet" href="assets_h/vendors/language-switcher/polyglot-language-switcher.css">
    <!-- Module css -->
    <link rel="stylesheet" href="assets_h/css/module-css/01-header-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/02-banner-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/03-about-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/04-fact-counter-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/05-testimonial-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/06-partner-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/07-footer-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/08-blog-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/09-breadcrumb-section.css">
    <link rel="stylesheet" href="assets_h/css/module-css/10-contact.css">

    <!-- Template styles -->
    <link rel="stylesheet" href="assets_h/css/style.css" />
    <link rel="stylesheet" href="assets_h/css/color-1.css">
    <link rel="stylesheet" href="assets_h/css/responsive.css" />

</head>


<body>

    <!-- Start preloader -->
    <div class="loader-wrap">
        <div class="preloader">
            <div class="preloader-close">x</div>
            <div id="handle-preloader" class="handle-preloader">
                <div class="animation-preloader">
                    <div class="spinner"></div>
                    <div class="txt-loading">
                        <span data-text-preloader="Z" class="letters-loading">
                            Z
                        </span>
                        <span data-text-preloader="E" class="letters-loading">
                            E
                        </span>
                        <span data-text-preloader="N" class="letters-loading">
                            N
                        </span>
                        <span data-text-preloader="T" class="letters-loading">
                            T
                        </span>
                        <span data-text-preloader="R" class="letters-loading">
                            R
                        </span>
                        <span data-text-preloader="A" class="letters-loading">
                            A
                        </span>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End preloader -->


    <div class="page-wrapper">

        <header class="main-header main-header-style2">

            <!--Start Main Header Style2 Top-->
            <div class="main-header-style2__top">
                <div class="auto-container">
                    <div class="outer-box">
                        <!--Start Main Header Style2 Top Left-->
                        <div class="main-header-style2__top-left">
                            <div class="notification-box">
                                <div class="icon">
                                    <span class="icon-notification"></span>
                                </div>
                                <p>
                                    Get upto 4%* on our Savings Account Balances with <?php echo $sitename ?> Bank.
                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                </p>
                            </div>
                        </div>
                        <!--End Main Header Style2 Top Left-->

                        <!--Start Main Header Style2 Top Right-->
                        <div class="main-header-style2__top-right">
                            <div class="header-menu-style1">
                                <ul>
                                    <li><a href="./#">Careers</a></li>
                                    <li><a href="./#">Faq’s</a></li>
                                    <li><a href="./#">Offers</a></li>
                                    <li><a href="./#">Calendar</a></li>
                                </ul>
                            </div>
                            <div class="box-search-style1">
                                <a href="./#" class="search-toggler">
                                    <span class="icon-search"></span>
                                    Search
                                </a>
                            </div>
                            <div class="language-switcher">
                                <div id="polyglotLanguageSwitcher">
                                    <form action="./#">
                                        <select id="polyglot-language-options">
                                            <option id="en" value="en" selected>English</option>
                                            <option id="fr" value="fr">French</option>
                                            <option id="de" value="de">German</option>
                                            <option id="it" value="it">Italian</option>
                                            <option id="es" value="es">Spanish</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--End Main Header Style2 Top Right-->

                    </div>
                </div>
            </div>
            <!--End Main Header Style2 Top-->

            <nav class="main-menu main-menu-style2">
                <div class="main-menu__wrapper clearfix">
                    <div class="container">
                        <div class="main-menu__wrapper-inner">

                            <div class="main-menu-style2-left">
                                <div class="logo-box-style2">
                                    <a href="#">
                                        <img style="width:200px !important;" src="images/IMG_09099.png" alt="Awesome Logo" title="">
                                    </a>
                                </div>

                                <div class="looking-banking-box looking-banking-box--style2">
                                    <div class="icon">
                                        <span class="icon-binoculars"></span>
                                    </div>
                                    <div class="select-box clearfix">
                                        <p>Looking</p>
                                        <select class="wide">
                                            <option data-display="Personal Banking">
                                                Personal Banking
                                            </option>
                                            <option value="1">Business Banking</option>
                                            <option value="2">Personal Banking</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="main-menu-style2-right">

                                <div class="main-menu-box">
                                    <a href="./#" class="mobile-nav__toggler">
                                        <i class="icon-menu"></i>
                                    </a>

                                    <ul class="main-menu__list one-page-scroll-menu">
                                        <li class="scrollToLink">
                                            <a href="./#home">Home</a>
                                        </li>
                                        <li class="scrollToLink">
                                            <a href="./#about">About</a>
                                        </li>
                                        <li class="scrollToLink">
                                            <a href="./#service">Services</a>
                                        </li>
                                        <li class="scrollToLink">
                                            <a href="./#news">News</a>
                                        </li>
                                        <li class="scrollToLink">
                                            <a href="./#contact">Get In Touch</a>
                                        </li>
                                        <li class="">
                                            <a href="./auth/sign_up/">Investment</a>
                                        </li>
                                    </ul>

                                </div>

                                <div class="main-menu-style1-right">
                                    <div class="header-btn-one">
                                        <a href="./auth/sign_in/" target="_blank">
                                            <span class="icon-home-button"></span>Login
                                        </a>
                                        <a class="style2" href="./auth/sign_up/" target="_blank">
                                            <span class="icon-payment"></span>Open an Account
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </nav>

        </header>


        <div class="stricky-header stricky-header--style2 stricked-menu main-menu">
            <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
        </div><!-- /.stricky-header -->


        <!--Main Slider Start-->
        <section id="home" class="main-slider main-slider-style2">
            <div class="swiper-container thm-swiper__slider" data-swiper-options='{"slidesPerView": 1, "loop": true,
                "effect": "fade",
                "pagination": {
                "el": "#main-slider-pagination",
                "type": "bullets",
                "clickable": true
                },
                "navigation": {
                "nextEl": "#main-slider__swiper-button-next",
                "prevEl": "#main-slider__swiper-button-prev"
                },
                "autoplay": {
                "delay": 5000
                }}'>
                <div class="swiper-wrapper">

                    <!--Start Single Swiper Slide-->
                    <div class="swiper-slide">
                        <div class="content-layer">
                            <div class="main-slider-content">
                                <div class="main-slider-content__inner">
                                    <div class="big-title">
                                        <h2>Providing<br> the best future<br> for your best <br> living.</h2>
                                    </div>
                                    <div class="text">
                                        <p>
                                            Don’t just make a deposit, make an investment today.
                                        </p>
                                    </div>
                                    <div class="btns-box">
                                        <a class="btn-one" href="./auth/sign_in/">
                                            <span class="txt">
                                                Login
                                            </span>
                                        </a>
                                        <a class="btn-one style2" href="./auth/sign_up/">
                                            <span class="txt">
                                                Register
                                            </span>
                                        </a>
                                    </div>
                                    <div class="bottom-text">
                                        <p><span>*</span> In a free hour, when our power.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="image-layer" style="background-image: url(assets_h/images/slides/slide-v2-1.png);">
                            <!--Start Slide Single Features-->
                            <div class="slide-single-features one">
                                <span class="icon-accept"></span>
                                <h3>From 6.65% p.a</h3>
                            </div>
                            <!--End Slide Single Features-->
                            <!--Start Slide Single Features-->
                            <div class="slide-single-features two">
                                <span class="icon-accept"></span>
                                <h3>High Repayment Tenure </h3>
                            </div>
                            <!--End Slide Single Features-->
                        </div>
                    </div>
                    <!--End Single Swiper Slide-->

                    <!--Start Single Swiper Slide-->
                    <div class="swiper-slide">
                        <div class="content-layer">
                            <div class="main-slider-content">
                                <div class="main-slider-content__inner">
                                    <div class="big-title">
                                        <h2><?php echo $sitename ?> bank<br> makes access to<br> savings fast & <br> simple.</h2>
                                    </div>
                                    <div class="text">
                                        <p>
                                            We help businesses and customers achieve more.
                                        </p>
                                    </div>
                                    <div class="btns-box">
                                        <a class="btn-one" href="./auth/sign_in/">
                                            <span class="txt">
                                                Login
                                            </span>
                                        </a>
                                        <a class="btn-one style2" href="./auth/sign_up/">
                                            <span class="txt">
                                                Register
                                            </span>
                                        </a>
                                    </div>
                                    <div class="bottom-text">
                                        <p><span>*</span> In a free hour, when our power.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="image-layer" style="background-image: url(assets_h/images/slides/slide-v2-2.jpg);">
                            <!--Start Slide Single Features-->
                            <div class="slide-single-features one">
                                <span class="icon-accept"></span>
                                <h3>6.5k Personal Account</h3>
                            </div>
                            <!--End Slide Single Features-->
                            <!--Start Slide Single Features-->
                            <div class="slide-single-features two">
                                <span class="icon-accept"></span>
                                <h3>14.2k Corporate Account</h3>
                            </div>
                            <!--End Slide Single Features-->
                        </div>
                    </div>
                    <!--End Single Swiper Slide-->

                    <!--Start Single Swiper Slide-->
                    <div class="swiper-slide">
                        <div class="content-layer">
                            <div class="main-slider-content">
                                <div class="main-slider-content__inner">
                                    <div class="big-title">
                                        <h2>Bank with<br> the happiest<br> employees in the <br> country.</h2>
                                    </div>
                                    <div class="text">
                                        <p>
                                            We help businesses and customers achieve more.
                                        </p>
                                    </div>
                                    <div class="btns-box">
                                        <a class="btn-one" href="./auth/sign_in/">
                                            <span class="txt">
                                                Login
                                            </span>
                                        </a>
                                        <a class="btn-one style2" href="./auth/sign_up/">
                                            <span class="txt">
                                                Investment
                                            </span>
                                        </a>
                                    </div>
                                    <div class="bottom-text">
                                        <p><span>*</span> In a free hour, when our power.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="image-layer" style="background-image: url(assets_h/images/slides/slide-v2-3.png);">
                            <!--Start Slide Single Features-->
                            <div class="slide-single-features one">
                                <span class="icon-accept"></span>
                                <h3>86 Branches In Country</h3>
                            </div>
                            <!--End Slide Single Features-->
                            <!--Start Slide Single Features-->
                            <div class="slide-single-features two">
                                <span class="icon-accept"></span>
                                <h3>1.6k On Role Employees</h3>
                            </div>
                            <!--End Slide Single Features-->
                        </div>
                    </div>
                    <!--End Single Swiper Slide-->

                    <!-- If we need navigation buttons -->
                    <div class="main-slider__nav main-slider__nav--style2">
                        <div class="swiper-button-prev" id="main-slider__swiper-button-next">
                            <i class="icon-chevron left"></i>
                        </div>
                        <div class="swiper-button-next" id="main-slider__swiper-button-prev">
                            <i class="icon-chevron right"></i>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--Main Slider End-->


        <!--Start Intro Style1 Area-->
        <section id="about" class="intro-style1-area" style="background-color: #f5f8f7;">
            <div class="container">
                <div class="row">

                    <div class="col-xl-6">
                        <div class="intro-style1-video-gallery">
                            <div class="intro-style1-video-gallery-bg"
                                style="background-image: url(assets_h/images/resources/intro-style1-video-gallery.jpg);">
                            </div>
                            <div class="intro-video-gallery-style1">
                                <div class="icon wow zoomIn animated" data-wow-delay="300ms" data-wow-duration="1500ms">
                                    <a class="video-popup" title="Video Gallery"
                                        href="index.html">
                                        <span class="icon-play-button-1"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="intro-style1-content-box">
                            <div class="sec-title">
                                <h2>Known for Trust,<br> Honesty & Customer<br> Support</h2>
                            </div>
                            <div class="text">
                                <p>Belongs to those who fail in their duty through weakness of will, which is
                                    the same as saying through shrinking from toil and pain. These cases are
                                    perfectly simple and easy to distinguish.</p>

                                <p>Choice is untrammelled and when nothing prevents our being able to do
                                    what we like best every pleasure is to be welcomed. </p>
                            </div>

                            <div class="row">
                                <!--Start Intro Style1 Single Box-->
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="intro-style1-single-box">
                                        <div class="img-box">
                                            <div class="img-box-inner">
                                                <img src="assets_h/images/resources/intro-style1-1.jpg" alt="">
                                            </div>
                                            <div class="overlay-text">
                                                <h3>Our Journey</h3>
                                            </div>
                                        </div>
                                        <div class="title-box">
                                            <h3><a href="./#">For Over Four Decades Our Bank</a></h3>
                                        </div>
                                    </div>
                                </div>
                                <!--End Intro Style1 Single Box-->
                                <!--Start Intro Style1 Single Box-->
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="intro-style1-single-box">
                                        <div class="img-box">
                                            <div class="img-box-inner">
                                                <img src="assets_h/images/resources/intro-style1-2.jpg" alt="">
                                            </div>
                                            <div class="overlay-text">
                                                <h3>Our Team</h3>
                                            </div>
                                        </div>
                                        <div class="title-box">
                                            <h3><a href="./#">Passion & Professional Management</a></h3>
                                        </div>
                                    </div>
                                </div>
                                <!--End Intro Style1 Single Box-->
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--End Intro Style1 Area-->














        <!--Start Individual Corporate Banking area-->
        <section id="service" class="individual-corporate-banking-area">
            <div class="container">
                <div class="row">

                    <div class="col-xl-6">
                        <div class="individual-banking">
                            <div class="individual-banking-bg"
                                style="background-image: url(assets_h/images/resources/individual-banking-bg.jpg);"></div>
                            <div class="individual-banking__inner">
                                <div class="sec-title">
                                    <h2>Banking for Individuals</h2>
                                    <div class="sub-title">
                                        <p>Offers a wide range of personal banking services.</p>
                                    </div>
                                </div>
                                <div class="individual-banking__inner-content">
                                    <ul>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v1-1.png" alt="">
                                                </div>
                                                <h3><a href="./#">Savings &<br> CDs</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v1-2.png" alt="">
                                                </div>
                                                <h3><a href="./#">Online &<br> Mobile</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v1-3.png" alt="">
                                                </div>
                                                <h3><a href="./#">Cosumer<br> Loan</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>

                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v1-4.png" alt="">
                                                </div>
                                                <h3><a href="./#">Invest &<br> Insure</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v1-5.png" alt="">
                                                </div>
                                                <h3><a href="./#">Credit &<br> Debit Cards</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking more-services">
                                                <div class="more-service-box">
                                                    <a href="./#"><span class="icon-add"></span></a>
                                                    <h3>More Service</h3>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-6">
                        <div class="corporate-banking">
                            <div class="corporate-banking-bg"
                                style="background-image: url(assets_h/images/resources/corporate-banking-bg.jpg);"></div>
                            <div class="corporate-banking__inner">
                                <div class="sec-title">
                                    <h2>Banking for Corporate</h2>
                                    <div class="sub-title">
                                        <p>High end banking solutions to large corporate.</p>
                                    </div>
                                </div>
                                <div class="corporate-banking__inner-content">
                                    <ul>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking single-features-of-banking--style2">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v2-1.png" alt="">
                                                </div>
                                                <h3><a href="./#">Commercial<br> C/A</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking single-features-of-banking--style2">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v2-2.png" alt="">
                                                </div>
                                                <h3><a href="./#">Online &<br> Mobile</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking single-features-of-banking--style2">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v2-3.png" alt="">
                                                </div>
                                                <h3><a href="./#">Business<br> Loan</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking single-features-of-banking--style2">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v2-4.png" alt="">
                                                </div>
                                                <h3><a href="./#">Invest &<br> Insure</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div class="single-features-of-banking single-features-of-banking--style2">
                                                <div class="icon-holder">
                                                    <img src="assets_h/images/icon/banking-features-v2-5.png" alt="">
                                                </div>
                                                <h3><a href="./#">Cash<br> Management</a></h3>
                                                <div class="btn-box">
                                                    <a href="./#"><span class="icon-right-arrow"></span></a>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>
                                        <li>
                                            <!--Start Single Features Of Banking-->
                                            <div
                                                class="single-features-of-banking single-features-of-banking--style2 more-services">
                                                <div class="more-service-box">
                                                    <a href="./#"><span class="icon-add"></span></a>
                                                    <h3>More Service</h3>
                                                </div>
                                            </div>
                                            <!--End Single Features Of Banking-->
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--End Individual Corporate Banking area-->


        <!--Start EMI Calculator Style2 Area-->
        <section class="emi-calculator-style2-area">
            <div class="container">
                <div class="sec-title text-center">
                    <h2>Flexible EMI Calculator Online</h2>
                    <div class="sub-title">
                        <p>Easily calculate your equated monthly instalment online.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="emi-calculator-tab emi-calculator-tab--style2">

                            <div class="emi-calculator-tab__button">
                                <div class="emi-calculator-tab__button--bg"
                                    style="background-image: url(assets_h/images/resources/emi-calculator.jpg);"></div>
                                <ul class="tabs-button-box">
                                    <li data-tab="#home-loan" class="tab-btn-item active-btn-item">
                                        <div class="icon-box">
                                            <span class="icon-loan-1"></span>
                                            <div class="overlay-text">
                                                <p>Home Loan</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li data-tab="#personal-loan" class="tab-btn-item">
                                        <div class="icon-box">
                                            <span class="icon-loan-2"></span>
                                            <div class="overlay-text">
                                                <p>Personal Loan</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li data-tab="#vehicle-loan" class="tab-btn-item">
                                        <div class="icon-box">
                                            <span class="icon-car-loan"></span>
                                            <div class="overlay-text">
                                                <p>Vehicle Loan</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="emi-calculator-tab-content-box-outer">
                                <!--Start Tabs Content Box-->
                                <div class="tabs-content-box">
                                    <!--Tab-->
                                    <div class="tab-content-box-item tab-content-box-item-active" id="home-loan">
                                        <div class="emi-calculator-tab-content-box-item">

                                            <div class="range-box">
                                                <div class="row">
                                                    <div class="col-lg-12 column">
                                                        <div class="price-range-box">
                                                            <div class="inner">
                                                                <h4>Loan Amount</h4>
                                                                <div class="price-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="property-amount"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>$1,000,000</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 column">
                                                        <div class="loan-term-range-box">
                                                            <div class="inner">
                                                                <h4>Loan Term <span>(Years)</span></h4>
                                                                <div class="loan-term-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="loan-term-range"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>20Yrs</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 column">
                                                        <div class="interest-rate-range-box">
                                                            <div class="inner">
                                                                <h4>Interest Rate</h4>
                                                                <div class="interest-rate-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="interest-rate-range"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>8%</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="emi-calculator-output-box clearfix">
                                                <div class="left-box">
                                                    <div class="top">
                                                        <div class="icon">
                                                            <span class="icon-loan-3"></span>
                                                        </div>
                                                        <div class="inner-title">
                                                            <h3>Your Monthly EMI</h3>
                                                            <h2><span>$</span>10,250</h2>
                                                        </div>
                                                    </div>
                                                    <div class="btns-box">
                                                        <a class="btn-one" href="./#">
                                                            <span class="txt">Apply Online</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="right-box">
                                                    <ul>
                                                        <li>
                                                            <div class="inner">
                                                                <div class="icon">
                                                                    <span class="icon-right-arrow"></span>
                                                                </div>
                                                                <div class="text">
                                                                    <a href="./#">Interest Amount</a>
                                                                    <p>$720,260</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="inner">
                                                                <div class="icon">
                                                                    <span class="icon-right-arrow"></span>
                                                                </div>
                                                                <div class="text">
                                                                    <a href="./#">Total Amount Payble</a>
                                                                    <p>$720,260</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <!--Tab-->
                                    <div class="tab-content-box-item" id="personal-loan">
                                        <div class="emi-calculator-tab-content-box-item">

                                            <div class="range-box">
                                                <div class="row">
                                                    <div class="col-lg-12 column">
                                                        <div class="price-range-box">
                                                            <div class="inner">
                                                                <h4>Loan Amount</h4>
                                                                <div class="price-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="property-amount"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>$1,000,000</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 column">
                                                        <div class="loan-term-range-box">
                                                            <div class="inner">
                                                                <h4>Loan Term <span>(Years)</span></h4>
                                                                <div class="loan-term-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="loan-term-range"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>20Yrs</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 column">
                                                        <div class="interest-rate-range-box">
                                                            <div class="inner">
                                                                <h4>Interest Rate</h4>
                                                                <div class="interest-rate-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="interest-rate-range"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>8%</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="emi-calculator-output-box clearfix">
                                                <div class="left-box">
                                                    <div class="top">
                                                        <div class="icon">
                                                            <span class="icon-loan-3"></span>
                                                        </div>
                                                        <div class="inner-title">
                                                            <h3>Your Monthly EMI</h3>
                                                            <h2><span>$</span>10,250</h2>
                                                        </div>
                                                    </div>
                                                    <div class="btns-box">
                                                        <a class="btn-one" href="./#">
                                                            <span class="txt">Apply Online</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="right-box">
                                                    <ul>
                                                        <li>
                                                            <div class="inner">
                                                                <div class="icon">
                                                                    <span class="icon-right-arrow"></span>
                                                                </div>
                                                                <div class="text">
                                                                    <a href="./#">Interest Amount</a>
                                                                    <p>$720,260</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="inner">
                                                                <div class="icon">
                                                                    <span class="icon-right-arrow"></span>
                                                                </div>
                                                                <div class="text">
                                                                    <a href="./#">Total Amount Payble</a>
                                                                    <p>$720,260</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <!--Tab-->
                                    <div class="tab-content-box-item" id="vehicle-loan">
                                        <div class="emi-calculator-tab-content-box-item">

                                            <div class="range-box">
                                                <div class="row">
                                                    <div class="col-lg-12 column">
                                                        <div class="price-range-box">
                                                            <div class="inner">
                                                                <h4>Loan Amount</h4>
                                                                <div class="price-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="property-amount"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>$1,000,000</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 column">
                                                        <div class="loan-term-range-box">
                                                            <div class="inner">
                                                                <h4>Loan Term <span>(Years)</span></h4>
                                                                <div class="loan-term-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="loan-term-range"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>20Yrs</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 column">
                                                        <div class="interest-rate-range-box">
                                                            <div class="inner">
                                                                <h4>Interest Rate</h4>
                                                                <div class="interest-rate-range-slider"></div>
                                                                <div class="range-input">
                                                                    <div class="input">
                                                                        <input type="text" class="interest-rate-range"
                                                                            name="field-name" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right-box">
                                                                <h5>8%</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="emi-calculator-output-box clearfix">
                                                <div class="left-box">
                                                    <div class="top">
                                                        <div class="icon">
                                                            <span class="icon-loan-3"></span>
                                                        </div>
                                                        <div class="inner-title">
                                                            <h3>Your Monthly EMI</h3>
                                                            <h2><span>$</span>10,250</h2>
                                                        </div>
                                                    </div>
                                                    <div class="btns-box">
                                                        <a class="btn-one" href="./#">
                                                            <span class="txt">Apply Online</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="right-box">
                                                    <ul>
                                                        <li>
                                                            <div class="inner">
                                                                <div class="icon">
                                                                    <span class="icon-right-arrow"></span>
                                                                </div>
                                                                <div class="text">
                                                                    <a href="./#">Interest Amount</a>
                                                                    <p>$720,260</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="inner">
                                                                <div class="icon">
                                                                    <span class="icon-right-arrow"></span>
                                                                </div>
                                                                <div class="text">
                                                                    <a href="./#">Total Amount Payble</a>
                                                                    <p>$720,260</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                                <!--End Tabs Content Box-->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End EMI Calculator Style2 Area-->

        <!--Start Wealth Secure Style2 Area-->
        <section class="wealth-secure-style2-area">
            <div class="container">
                <div class="sec-title">
                    <h2>Grow Your Wealth Secure</h2>
                    <div class="sub-title">
                        <p>Don’t just make a deposit, make an investment today.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="owl-carousel owl-theme thm-owl__carousel wealth-secure-carousel owl-nav-style-one"
                            data-owl-options='{
                            "loop": true,
                            "autoplay": true,
                            "margin": 20,
                            "nav": true,
                            "dots": false,
                            "smartSpeed": 500,
                            "autoplayTimeout": 10000,
                            "navText": ["<span class=\"left icon-right-arrow\"></span>","<span class=\"right icon-right-arrow\"></span>"],
                            "responsive": {
                                    "0": {
                                        "items": 1
                                    },
                                    "768": {
                                        "items": 2
                                    },
                                    "992": {
                                        "items": 3
                                    },
                                    "1200": {
                                        "items": 4
                                    }
                                }
                            }'>

                            <!--Start Single Wealth Secure Box Style2-->
                            <div class="single-wealth-secure-box single-wealth-secure-box--style2">
                                <div class="img-box">
                                    <div class="img-box-inner">
                                        <img src="assets_h/images/resources/wealth-secure-v2-1.jpg" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3><a href="./#">Trade FX</a></h3>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <p>Beguiled & demoralized by pleasure of moment.</p>
                                    <ul>
                                        <li>Futures Market</li>
                                        <li>Forward Market</li>
                                        <li>Swap Market</li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Single Wealth Secure Box Style2-->
                            <!--Start Single Wealth Secure Box Style2-->
                            <div class="single-wealth-secure-box single-wealth-secure-box--style2">
                                <div class="img-box">
                                    <div class="img-box-inner">
                                        <img src="assets_h/images/resources/wealth-secure-v2-2.jpg" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3><a href="./#">Multi Currency a/c</a></h3>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <p>Perfectly simple distinguish our power of choice.</p>
                                    <ul>
                                        <li>10+ Currencies</li>
                                        <li>Transaction Costs</li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Single Wealth Secure Box Style2-->
                            <!--Start Single Wealth Secure Box Style2-->
                            <div class="single-wealth-secure-box single-wealth-secure-box--style2">
                                <div class="img-box">
                                    <div class="img-box-inner">
                                        <img src="assets_h/images/resources/wealth-secure-v2-3.jpg" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3><a href="./#">Mutual Funds</a></h3>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <p>Duty obligations business it will frequently occur.</p>
                                    <ul>
                                        <li>Money Market Funds</li>
                                        <li>Debt Mutual Funds</li>
                                        <li>Balanced Funds</li>
                                        <li>Monthly Income Plans</li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Single Wealth Secure Box Style2-->
                            <!--Start Single Wealth Secure Box Style2-->
                            <div class="single-wealth-secure-box single-wealth-secure-box--style2">
                                <div class="img-box">
                                    <div class="img-box-inner">
                                        <img src="assets_h/images/resources/wealth-secure-v2-4.jpg" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3><a href="./#">Pension Scheme</a></h3>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <p>The wise therefore always holds matters this.</p>
                                    <ul>
                                        <li>NPS</li>
                                        <li>Public PF</li>
                                        <li>Employee PF</li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Single Wealth Secure Box Style2-->

                            <!--Start Single Wealth Secure Box Style2-->
                            <div class="single-wealth-secure-box single-wealth-secure-box--style2">
                                <div class="img-box">
                                    <div class="img-box-inner">
                                        <img src="assets_h/images/resources/wealth-secure-v2-1.jpg" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3><a href="./#">Trade FX</a></h3>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <p>Beguiled & demoralized by pleasure of moment.</p>
                                    <ul>
                                        <li>Futures Market</li>
                                        <li>Forward Market</li>
                                        <li>Swap Market</li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Single Wealth Secure Box Style2-->
                            <!--Start Single Wealth Secure Box Style2-->
                            <div class="single-wealth-secure-box single-wealth-secure-box--style2">
                                <div class="img-box">
                                    <div class="img-box-inner">
                                        <img src="assets_h/images/resources/wealth-secure-v2-2.jpg" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3><a href="./#">Multi Currency a/c</a></h3>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <p>Perfectly simple distinguish our power of choice.</p>
                                    <ul>
                                        <li>10+ Currencies</li>
                                        <li>Transaction Costs</li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Single Wealth Secure Box Style2-->
                            <!--Start Single Wealth Secure Box Style2-->
                            <div class="single-wealth-secure-box single-wealth-secure-box--style2">
                                <div class="img-box">
                                    <div class="img-box-inner">
                                        <img src="assets_h/images/resources/wealth-secure-v2-3.jpg" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3><a href="./#">Mutual Funds</a></h3>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <p>Duty obligations business it will frequently occur.</p>
                                    <ul>
                                        <li>Money Market Funds</li>
                                        <li>Debt Mutual Funds</li>
                                        <li>Balanced Funds</li>
                                        <li>Monthly Income Plans</li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Single Wealth Secure Box Style2-->
                            <!--Start Single Wealth Secure Box Style2-->
                            <div class="single-wealth-secure-box single-wealth-secure-box--style2">
                                <div class="img-box">
                                    <div class="img-box-inner">
                                        <img src="assets_h/images/resources/wealth-secure-v2-4.jpg" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3><a href="./#">Pension Scheme</a></h3>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <p>The wise therefore always holds matters this.</p>
                                    <ul>
                                        <li>NPS</li>
                                        <li>Public PF</li>
                                        <li>Employee PF</li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Single Wealth Secure Box Style2-->

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Wealth Secure Style2 Area-->





        <!--Start Locker Facility Area-->
        <section class="locker-facility-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="locker-facility-highlights">
                            <div class="single-box one">
                                <div class="icon">
                                    <span class="icon-checkbox-mark"></span>
                                </div>
                                <p>Choose your locker sizes</p>
                            </div>
                            <div class="single-box two">
                                <div class="icon">
                                    <span class="icon-checkbox-mark"></span>
                                </div>
                                <p>Book from anywhere</p>
                            </div>
                            <div class="single-box three">
                                <div class="icon">
                                    <span class="icon-checkbox-mark"></span>
                                </div>
                                <p>Facility of Nomination</p>
                            </div>
                            <div class="img-box">
                                <div class="inner">
                                    <img class="float-bob-y" src="assets_h/images/resources/locker-facility.png" alt="">
                                    <div class="icon">
                                        <span class="icon-protection"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span><span class="path5"></span><span
                                                class="path6"></span><span class="path7"></span><span
                                                class="path8"></span><span class="path9"></span><span
                                                class="path10"></span><span class="path11"></span><span
                                                class="path12"></span><span class="path13"></span><span
                                                class="path14"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="locker-facility-text-box">
                            <div class="sec-title">
                                <h2>Best Locker<br> Facility For Your<br> Valuables</h2>
                            </div>
                            <div class="text-box">
                                <p>Perfectly simple and easy to distinguish. In a free
                                    hour when our power off choices is untrammelled
                                    best pleasure is to be welcomed every pleasures
                                    to be welcomed every avoided.</p>
                            </div>
                            <div class="btns-box">
                                <a class="btn-one" href="./#">
                                    <span class="txt">Online Booking</span>
                                </a>
                            </div>
                            <div class="faq-question-btn">
                                <div class="icon">
                                    <span class="icon-faq"></span>
                                </div>
                                <p>Have queries? Click below link</p>
                                <a href="./#"><span class="icon-right-arrow"></span>faq’s</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--End Locker Facility Area-->

        <!--Start Facts Area-->
        <section class="facts-area">
            <div class="facts-area-bg" style="background-image: url(assets_h/images/backgrounds/facts-area-bg.jpg);">
            </div>
            <div class="container">
                <div class="sec-title text-center">
                    <h2>Few Interesting Numbers</h2>
                    <div class="sub-title">
                        <p>Numbers that speak about banking service.</p>
                    </div>
                </div>
                <div class="row">
                    <!--Start Single Fact Box-->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="single-fact-box">
                            <div class="icon">
                                <span class="icon-bank"><span class="path1"></span><span class="path2"></span><span
                                        class="path3"></span><span class="path4"></span><span class="path5"></span><span
                                        class="path6"></span><span class="path7"></span><span class="path8"></span><span
                                        class="path9"></span><span class="path10"></span><span
                                        class="path11"></span><span class="path12"></span><span
                                        class="path13"></span><span class="path14"></span><span
                                        class="path15"></span><span class="path16"></span></span>
                            </div>
                            <div class="text">
                                <h3>Our Network</h3>
                                <p>86 Branches around the country</p>
                            </div>
                        </div>
                    </div>
                    <!--End Single Fact Box-->

                    <!--Start Single Fact Box-->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="single-fact-box">
                            <div class="icon">
                                <span class="icon-expert"><span class="path1"></span><span class="path2"></span></span>
                            </div>
                            <div class="text">
                                <h3>Customers</h3>
                                <p>More than 1.5 illion customers</p>
                            </div>
                        </div>
                    </div>
                    <!--End Single Fact Box-->

                    <!--Start Single Fact Box-->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="single-fact-box">
                            <div class="icon">
                                <span class="icon-human"><span class="path1"></span><span class="path2"></span><span
                                        class="path3"></span><span class="path4"></span><span class="path5"></span><span
                                        class="path6"></span><span class="path7"></span><span class="path8"></span><span
                                        class="path9"></span><span class="path10"></span><span
                                        class="path11"></span><span class="path12"></span><span
                                        class="path13"></span><span class="path14"></span><span
                                        class="path15"></span></span>
                            </div>
                            <div class="text">
                                <h3>Employee</h3>
                                <p>1.6k professional employees</p>
                            </div>
                        </div>
                    </div>
                    <!--End Single Fact Box-->

                    <!--Start Single Fact Box-->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="single-fact-box">
                            <div class="icon">
                                <span class="icon-money-bag"><span class="path1"></span><span
                                        class="path2"></span></span>
                            </div>
                            <div class="text">
                                <h3>Loans Disbursed</h3>
                                <p>45.6 Cr loans for 258 customers</p>
                            </div>
                        </div>
                    </div>
                    <!--End Single Fact Box-->

                </div>
            </div>
        </section>
        <!--End Facts Area-->

        <!--Start Features Style4 Area-->
        <section class="features-style4-area">
            <div class="container">
                <div class="row">

                    <div class="col-xl-4">
                        <div class="features-style4-title-box">
                            <div class="sec-title">
                                <h2><?php echo $sitename ?><br> Super Savings<br> a/c in 5 Mins</h2>
                                <div class="sub-title">
                                    <p>Blinded by desire that they cannot foresee
                                        pain & trouble that are bound.</p>
                                </div>
                            </div>
                            <div class="btns-box">
                                <a class="btn-one" href="./#">
                                    <span class="txt">Open an Account</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="app-screen">
                            <img src="assets_h/images/resources/app-screen.png" alt="">
                        </div>
                    </div>

                    <div class="col-xl-5">
                        <div class="account-creation-step">
                            <ul>
                                <li>
                                    <div class="single-step">
                                        <div class="counting">01</div>
                                        <div class="text-box">
                                            <h3>Submit Documents</h3>
                                            <p>Perfectly simple & easy to distinguish
                                                of choice is prevents.</p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="single-step">
                                        <div class="counting">02</div>
                                        <div class="text-box">
                                            <h3>KYC Verification</h3>
                                            <p>Claims off duty or the obligations it will
                                                pleasures be repudiated.</p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="single-step">
                                        <div class="counting lnone">03</div>
                                        <div class="text-box">
                                            <h3>Open an a/c & Deposit</h3>
                                            <p>Demoralized by charms pleasure of the
                                                they cannot and trouble.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--End Features Style4 Area-->

        <!--Start Offers Area-->
        <section class="offers-area">
            <div class="container">
                <div class="sec-title text-center">
                    <h2>Exciting Offers For You</h2>
                    <div class="sub-title">
                        <p>Enjoy exclusive deals & offers with our bank.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4">
                        <!--Start Single Offer Box-->
                        <div class="single-offer-box">
                            <div class="top">
                                <div class="offer-logo">
                                    <img src="assets_h/images/resources/offer-logo-1.png" alt="">
                                </div>
                                <div class="date-box">
                                    <p>Till: 25th Jun’22</p>
                                </div>
                            </div>
                            <div class="category">
                                <h4>Medical</h4>
                                <div class="border-box"></div>
                            </div>
                            <h3>Get 10% Cashback on Xfinity Restuarant.</h3>
                            <div class="bottom">
                                <div class="btn-box">
                                    <a href="./#"><span class="icon-right-arrow"></span>Know More</a>
                                </div>
                                <div class="share-btn">
                                    <a href="./#"><span class="icon-share"></span>Share</a>
                                </div>
                            </div>
                        </div>
                        <!--End Single Offer Box-->
                        <!--Start Single Offer Box-->
                        <div class="single-offer-box">
                            <div class="top">
                                <div class="offer-logo">
                                    <img src="assets_h/images/resources/offer-logo-2.png" alt="">
                                </div>
                                <div class="date-box">
                                    <p>Till: 10th Jul’22</p>
                                </div>
                            </div>
                            <div class="category">
                                <h4>Entertainment</h4>
                                <div class="border-box"></div>
                            </div>
                            <h3>Get 10% Cashback on Xfinity Restuarant.</h3>
                            <div class="bottom">
                                <div class="btn-box">
                                    <a href="./#"><span class="icon-right-arrow"></span>Know More</a>
                                </div>
                                <div class="share-btn">
                                    <a href="./#"><span class="icon-share"></span>Share</a>
                                </div>
                            </div>
                        </div>
                        <!--End Single Offer Box-->
                    </div>

                    <div class="col-xl-4">
                        <div class="subscribe-box-style1">
                            <div class="icon">
                                <img src="assets_h/images/icon/subscribe-1.png" alt="">
                            </div>
                            <div class="inner-title">
                                <h3>Subscribe Us</h3>
                                <p>Subscribe us & Stay updated.</p>
                            </div>
                            <form class="subscribe-form-style1" action="./#">
                                <div class="input-box">
                                    <input type="email" name="email" placeholder="Email address">
                                    <div class="inner-icon">
                                        <i class="far fa-envelope-open"></i>
                                    </div>
                                </div>
                                <button class="btn-one" type="submit">
                                    <span class="txt">Subscribe</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <!--Start Single Offer Box-->
                        <div class="single-offer-box">
                            <div class="top">
                                <div class="offer-logo">
                                    <img src="assets_h/images/resources/offer-logo-3.png" alt="">
                                </div>
                                <div class="date-box">
                                    <p>Till: 28th Jun’22</p>
                                </div>
                            </div>
                            <div class="category">
                                <h4>Dining</h4>
                                <div class="border-box"></div>
                            </div>
                            <h3>Get 10% Cashback on Xfinity Restuarant.</h3>
                            <div class="bottom">
                                <div class="btn-box">
                                    <a href="./#"><span class="icon-right-arrow"></span>Know More</a>
                                </div>
                                <div class="share-btn">
                                    <a href="./#"><span class="icon-share"></span>Share</a>
                                </div>
                            </div>
                        </div>
                        <!--End Single Offer Box-->
                        <!--Start Single Offer Box-->
                        <div class="single-offer-box">
                            <div class="top">
                                <div class="offer-logo">
                                    <img src="assets_h/images/resources/offer-logo-1.png" alt="">
                                </div>
                                <div class="date-box">
                                    <p>Till: 10th Jul’22</p>
                                </div>
                            </div>
                            <div class="category">
                                <h4>Medical</h4>
                                <div class="border-box"></div>
                            </div>
                            <h3>Get 10% Cashback on Xfinity Restuarant.</h3>
                            <div class="bottom">
                                <div class="btn-box">
                                    <a href="./#"><span class="icon-right-arrow"></span>Know More</a>
                                </div>
                                <div class="share-btn">
                                    <a href="./#"><span class="icon-share"></span>Share</a>
                                </div>
                            </div>
                        </div>
                        <!--End Single Offer Box-->
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="view-all-offer-btn">
                            <a href="./#"><span class="icon-right-arrow"></span>View All Offers</a>
                        </div>
                    </div>
                </div>


            </div>
        </section>
        <!--End Offers Area-->

        <!--Start Blog Style1 Area-->
        <section id="news" class="blog-style1-area">
            <div class="container">
                <div class="sec-title">
                    <h2>Latest From News Room</h2>
                    <div class="sub-title">
                        <p>Our blog post provides you all the updates & guides.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="owl-carousel owl-theme thm-owl__carousel blog-style1-carousel owl-nav-style-one"
                            data-owl-options='{
                            "loop": true,
                            "autoplay": true,
                            "margin": 30,
                            "nav": true,
                            "dots": false,
                            "smartSpeed": 500,
                            "autoplayTimeout": 10000,
                            "navText": ["<span class=\"left icon-right-arrow\"></span>","<span class=\"right icon-right-arrow\"></span>"],
                            "responsive": {
                                    "0": {
                                        "items": 1
                                    },
                                    "768": {
                                        "items": 1
                                    },
                                    "992": {
                                        "items": 2
                                    },
                                    "1200": {
                                        "items": 3
                                    }
                                }
                            }'>


                            <!--Start Single blog Style1-->
                            <div class="single-blog-style1 wow fadeInUp" data-wow-delay="00ms"
                                data-wow-duration="1500ms">
                                <div class="img-holder">
                                    <div class="inner">
                                        <img src="assets_h/images/blog/blog-v1-1.jpg" alt="">
                                        <div class="overlay-icon">
                                            <a href="https://<?php echo $sitename ?>ibnk.com/blog-single.html">
                                                <span class="icon-right-arrow"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="category-date-box">
                                        <div class="category">
                                            <span class="icon-play-button-1"></span>
                                            <h5>Banking</h5>
                                        </div>
                                        <div class="date">
                                            <h5>May 29, 2022</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-holder">
                                    <h3 class="blog-title">
                                        <a href="https://<?php echo $sitename ?>ibnk.com/blog-single.html">
                                            Including Animation In Your Design Technologies System
                                        </a>
                                    </h3>
                                    <div class="bottom">
                                        <div class="meta-box">
                                            <ul class="meta-info">
                                                <li><span class="icon-clock"></span> <a href="./#">6 Mins Read</a></li>
                                                <li><span class="icon-comment"></span> <a href="./#">10 Cmnts</a></li>
                                            </ul>
                                        </div>
                                        <div class="share-btn">
                                            <a href="./#"><span class="icon-share"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Single blog Style1-->
                            <!--Start Single blog Style1-->
                            <div class="single-blog-style1 wow fadeInUp" data-wow-delay="00ms"
                                data-wow-duration="1500ms">
                                <div class="img-holder">
                                    <div class="inner">
                                        <img src="assets_h/images/blog/blog-v1-2.jpg" alt="">
                                        <div class="overlay-icon">
                                            <a href="https://<?php echo $sitename ?>ibnk.com/blog-single.html">
                                                <span class="icon-right-arrow"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="category-date-box">
                                        <div class="category">
                                            <span class="icon-play-button-1"></span>
                                            <h5>Press Release</h5>
                                        </div>
                                        <div class="date">
                                            <h5>May 25, 2022</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-holder">
                                    <h3 class="blog-title">
                                        <a href="https://<?php echo $sitename ?>ibnk.com/blog-single.html">
                                            Board Approves Capital Raise of Rs. 2000 Crores
                                        </a>
                                    </h3>
                                    <div class="bottom">
                                        <div class="meta-box">
                                            <ul class="meta-info">
                                                <li><span class="icon-clock"></span> <a href="./#">4 Mins Read</a></li>
                                                <li><span class="icon-comment"></span> <a href="./#">10 Cmnts</a></li>
                                            </ul>
                                        </div>
                                        <div class="share-btn">
                                            <a href="./#"><span class="icon-share"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Single blog Style1-->
                            <!--Start Single blog Style1-->
                            <div class="single-blog-style1 wow fadeInUp" data-wow-delay="00ms"
                                data-wow-duration="1500ms">
                                <div class="img-holder">
                                    <div class="inner">
                                        <img src="assets_h/images/blog/blog-v1-3.jpg" alt="">
                                        <div class="overlay-icon">
                                            <a href="https://<?php echo $sitename ?>ibnk.com/blog-single.html">
                                                <span class="icon-right-arrow"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="category-date-box">
                                        <div class="category">
                                            <span class="icon-play-button-1"></span>
                                            <h5>Finance</h5>
                                        </div>
                                        <div class="date">
                                            <h5>May 10, 2022</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-holder">
                                    <h3 class="blog-title">
                                        <a href="https://<?php echo $sitename ?>ibnk.com/blog-single.html">
                                            The National Avg Interest Rate for Savings Accounts
                                        </a>
                                    </h3>
                                    <div class="bottom">
                                        <div class="meta-box">
                                            <ul class="meta-info">
                                                <li><span class="icon-clock"></span> <a href="./#">3 Mins Read</a></li>
                                                <li><span class="icon-comment"></span> <a href="./#">10 Cmnts</a></li>
                                            </ul>
                                        </div>
                                        <div class="share-btn">
                                            <a href="./#"><span class="icon-share"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Single blog Style1-->

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Blog Style1 Area-->

        <!--Start Awards Achivements Area-->
        <section class="awards-achivements-area">
            <div class="container">
                <div class="sec-title text-center">
                    <h2>Awards & Major Achivements</h2>
                    <div class="sub-title">
                        <p>Outstanding performance and achievements.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4">
                        <div class="awards-achivements-left-box">
                            <!--Start single awards achivements box -->
                            <div class="single-awards-achivements-box">
                                <div class="top">
                                    <div class="icon">
                                        <img src="assets_h/images/icon/award-1.png" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3>Bank of the Year<br> Europe</h3>
                                    </div>
                                </div>
                                <ul>
                                    <li>
                                        <span>Year</span><b>:</b> 2020-2021
                                    </li>
                                    <li>
                                        <span>Award by</span><b>:</b> Los Vegas Business Time
                                    </li>
                                </ul>
                            </div>
                            <!--End single awards achivements box -->
                            <!--Start single awards achivements box -->
                            <div class="single-awards-achivements-box">
                                <div class="top">
                                    <div class="icon">
                                        <img src="assets_h/images/icon/award-1.png" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3>Best Commercial<br> Bank Award</h3>
                                    </div>
                                </div>
                                <ul>
                                    <li>
                                        <span>Year</span><b>:</b> 2017-2018
                                    </li>
                                    <li>
                                        <span>Award by</span><b>:</b> Los Vegas Business Time
                                    </li>
                                </ul>
                            </div>
                            <!--End single awards achivements box -->
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="awards-img-box">
                            <div class="round-box"></div>
                            <div class="shape1">
                                <img src="assets_h/images/resources/trophy-shape-1.png" alt="">
                            </div>
                            <div class="shape2">
                                <img src="assets_h/images/resources/trophy-shape-2.png" alt="">
                            </div>
                            <div class="inner">
                                <img src="assets_h/images/resources/trophy.png" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="awards-achivements-right-box">
                            <!--Start single awards achivements box -->
                            <div class="single-awards-achivements-box">
                                <div class="top">
                                    <div class="icon">
                                        <img src="assets_h/images/icon/award-1.png" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3>Best Private Bank<br> Award</h3>
                                    </div>
                                </div>
                                <ul>
                                    <li>
                                        <span>Year</span><b>:</b> 2018-2019
                                    </li>
                                    <li>
                                        <span>Award by</span><b>:</b> Los Vegas Business Time
                                    </li>
                                </ul>
                            </div>
                            <!--End single awards achivements box -->
                            <!--Start single awards achivements box -->
                            <div class="single-awards-achivements-box">
                                <div class="top">
                                    <div class="icon">
                                        <img src="assets_h/images/icon/award-1.png" alt="">
                                    </div>
                                    <div class="inner-title">
                                        <h3>Banker’s Bank of the<br> Year Awards</h3>
                                    </div>
                                </div>
                                <ul>
                                    <li>
                                        <span>Year</span><b>:</b> 2014-2015
                                    </li>
                                    <li>
                                        <span>Award by</span><b>:</b> Los Vegas Business Time
                                    </li>
                                </ul>
                            </div>
                            <!--End single awards achivements box -->
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--End Awards Achivements Area-->


        <!--Start Main Contact Form Area-->
        <section id="contact" class="main-contact-form-area pdb100">
            <div class="container">
                <div class="row">

                    <div class="col-xl-6">
                        <div class="contact-info-box-style1">
                            <div class="box1"></div>
                            <div class="title">
                                <h2>Get Support for<br> any Queries or Complaints</h2>
                                <p>Committed to helping you meet all your banking needs.</p>
                            </div>

                            <ul class="contact-info-1">
                                <li>
                                    <div class="icon">
                                        <span class="icon-map"></span>
                                    </div>
                                    <div class="text">
                                        <p>Corporate Office</p>
                                        <h3>141, First Floor, 12 St RootsTerrace,<br>
                                            Los Angeles USA 90010.</h3>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-clock"></span>
                                    </div>
                                    <div class="text">
                                        <p>Office Hours</p>
                                        <h3>Mon - Fri: 9.00am to 5.00pm</h3>
                                        <span>[2nd Sat Holiday]</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-phone"></span>
                                    </div>
                                    <div class="text">
                                        <p>Front Desk</p>

                                        <h3><a href="mailto:yourmail@email.com">support@zentrapb.com</a></h3>
                                    </div>
                                </li>
                            </ul>

                            <div class="bottom-box">
                                <div class="btn-box">
                                    <a href="./#"><i class="fas fa-arrow-down"></i> Customer Care</a>
                                </div>
                                <div class="footer-social-link-style1">
                                    <ul class="clearfix">
                                        <li>
                                            <a href="./#">
                                                <i class="fab fa-youtube"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="./#">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="./#">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="./#">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="contact-form" style="background-color: #f5f8f7;">
                            <form id="contact-form" name="contact_form" class="default-form2"
                                method="post">

                                <div class="form-group">
                                    <label>Name</label>
                                    <div class="input-box">
                                        <input type="text" name="form_name" id="formName" placeholder="xxxxxx"
                                            required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <div class="input-box">
                                        <input type="email" name="form_email" id="formEmail" placeholder="" required="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Ph. Num</label>
                                    <div class="input-box">
                                        <input type="text" name="form_phone" value="" id="formPhone" placeholder="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Subject</label>
                                    <div class="input-box">
                                        <input type="text" name="form_subject" value="" id="formSubject"
                                            placeholder="Subject">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Message</label>
                                    <div class="input-box">
                                        <textarea name="form_message" id="formMessage" placeholder=""
                                            required=""></textarea>
                                    </div>
                                </div>

                                <div class="button-box">
                                    <input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden"
                                        value="">
                                    <button class="btn-one" type="submit" data-loading-text="Please wait...">
                                        <span class="txt">
                                            send a message
                                        </span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--End Main Contact Form Area-->



        <!--Start footer area -->
        <footer class="footer-area footer-area--style2">

            <!--Start Footer Top-->
            <div class="footer-top-style2">
                <div class="container">
                    <div class="row">

                        <div class="col-xl-7">
                            <div class="footer-top-style2__left-content">
                                <!--Start Our Company Info-->
                                <div class="our-company-info">
                                    <div class="footer-logo-style1">
                                        <a href="">
                                            <img src="images/IMG_09099.png " style="width:200px !important;" alt="Awesome Logo"
                                                title="">
                                        </a>
                                    </div>
                                    <div class="bottom-text2">
                                        <p>Simple and easy to distinguish. In a free hour when our power off best
                                            pleasure is to be welcomed every pleasures business it frequently occur
                                            that pleasures have to be repudiated.</p>
                                        <div class="btn-box">
                                            <a href="./#"><span class="icon-right-arrow"></span> More About Us</a>
                                        </div>
                                    </div>
                                </div>
                                <!--End Our Company Info-->
                                <!--Start Footer Top Style2 Left Content Inner-->
                                <div class="footer-top-style2__left-content-inner">
                                    <ul class="row">
                                        <li class="col-xl-4 col-lg-4 col-md-4">
                                            <div class="single-footer-widget single-footer-widget--link-box">
                                                <div class="footer-widget-links">
                                                    <ul>
                                                        <li><a href="./#">Home Loan</a></li>
                                                        <li><a href="./#">Car Loan</a></li>
                                                        <li><a href="./#">Two Wheeler Loan</a></li>
                                                        <li><a href="./#">Personal Loan</a></li>
                                                        <li><a href="./#">Tractor Loan</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="col-xl-4 col-lg-4 col-md-4">
                                            <div class="single-footer-widget single-footer-widget--link-box">
                                                <div class="footer-widget-links">
                                                    <ul>
                                                        <li><a href="./#">Cards</a></li>
                                                        <li><a href="./#">Deposits</a></li>
                                                        <li><a href="./#">Payments</a></li>
                                                        <li><a href="./#">Insurance</a></li>
                                                        <li><a href="./#">Locker Facility</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="col-xl-4 col-lg-4 col-md-4">
                                            <div class="single-footer-widget single-footer-widget--link-box">
                                                <div class="footer-widget-links">
                                                    <ul>
                                                        <li><a href="./#">Career</a></li>
                                                        <li><a href="./#">News Room</a></li>
                                                        <li><a href="./#">Interest Rates</a></li>
                                                        <li><a href="./#">Gold Rate Today</a></li>
                                                        <li><a href="./#">Charges & Fees</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                                <!--End Footer Top Style2 Left Content Inner-->
                            </div>
                        </div>

                        <div class="col-xl-5">
                            <div class="footer-top-style2__right-content">
                                <div class="footer-contact-info-style">
                                    <ul>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-map"></span>
                                            </div>
                                            <div class="text">
                                                <h3>Corporate</h3>
                                                <p>230, Second Floor, Maplewood Plaza,
                                                    West 7th Street, Los Angeles <br>
                                                    United States 90017.
                                                    </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-phone-call"></span>
                                            </div>
                                            <div class="text">
                                                <h3>Help Desk</h3>
                                                <p>Send a Mail: <a
                                                        href="mailto:<?php echo $siteemail ?>"><?php echo $siteemail ?></a>
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="find-nearest-branch-box">
                                    <div class="top-outer">
                                        <div class="top">
                                            <div class="icon">
                                                <span class="icon-bank-1"></span>
                                            </div>
                                            <div class="inner-title">
                                                <h3>Over 86 Branch,<br> Find Your Nearest One</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-box1">
                                        <form class="zip-form" action="./#">
                                            <input id="zip" name="zip" type="number" placeholder="Enter Zip Code...">
                                            <button type="submit">
                                                <i class="icon-right-arrow"></i>Find
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--End Footer Top-->


            <div class="footer-bottom-style2">
                <div class="container">
                    <div class="bottom-inner">
                        <div class="footer-menu">
                            <ul>
                                <li><a href="./#">&copy; 2022 <?php echo $sitename ?> </a></li>
                                <li><a href="./#">Privacy Policy</a></li>
                                <li><a href="./#">Terms & Conditions</a></li>
                                <li><a href="./#">Sitemap</a></li>
                            </ul>
                        </div>
                        <div class="scrool-top-btn-style2">
                            <a href="./#" data-target="html" class="scroll-to-target scroll-to-top--style2">
                                <i class="icon-diagonal-arrow"></i>Back to Top
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </footer>
        <!--End footer area-->


    </div>
    <!-- /.page-wrapper -->



    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler">
                <i class="fas fa-plus"></i>
            </span>
            <div class="logo-box">
                <a href="images/IMG_09099.png" aria-label="logo image">
                    <img style="width: 80px;" src="images/IMG_09099.png" alt="" />
                </a>
            </div>
            <div class="mobile-nav__container"></div>
            <ul class="mobile-nav__contact list-unstyled">
                <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:<?php echo $sitemail ?>"><?php echo $siteemail ?></a>
                </li>
            </ul>
            <div class="mobile-nav__social">
                <a href="./#" class="fab fa-twitter"></a>
                <a href="./#" class="fab fa-facebook-square"></a>
                <a href="./#" class="fab fa-pinterest-p"></a>
                <a href="./#" class="fab fa-instagram"></a>
            </div>
        </div>
    </div>


    <div class="search-popup">
        <div class="search-popup__overlay search-toggler"></div>
        <div class="search-popup__content">
            <form action="./#">
                <label for="search" class="sr-only">search here</label>
                <input type="text" id="search" placeholder="Search Here..." />
                <button type="submit" aria-label="search submit" class="thm-btn">
                    <i class="icon-search"></i>
                </button>
            </form>
        </div>
    </div>





    <script src="assets_h/vendors/jquery/jquery-3.6.0.min.js"></script>
    <script src="assets_h/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets_h/vendors/bxslider/jquery.bxslider.min.js"></script>
    <script src="assets_h/vendors/circleType/jquery.circleType.js"></script>
    <script src="assets_h/vendors/circleType/jquery.lettering.min.js"></script>
    <script src="assets_h/vendors/isotope/isotope.js"></script>
    <script src="assets_h/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js"></script>
    <script src="assets_h/vendors/jquery-appear/jquery.appear.min.js"></script>
    <script src="assets_h/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets_h/vendors/jquery-migrate/jquery-migrate.min.js"></script>
    <script src="assets_h/vendors/jquery-ui/jquery-ui.js"></script>
    <script src="assets_h/vendors/jquery-validate/jquery.validate.min.js"></script>
    <script src="assets_h/vendors/nice-select/jquery.nice-select.min.js"></script>
    <script src="assets_h/vendors/odometer/odometer.min.js"></script>
    <script src="assets_h/vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="assets_h/vendors/swiper/swiper.min.js"></script>
    <script src="assets_h/vendors/vegas/vegas.min.js"></script>
    <script src="assets_h/vendors/wnumb/wNumb.min.js"></script>
    <script src="assets_h/vendors/wow/wow.js"></script>
    <script src="assets_h/vendors/extra-scripts/jquery.paroller.min.js"></script>
    <script src="assets_h/vendors/language-switcher/jquery.polyglot.language.switcher.js"></script>

    <!-- Template js -->
    <script src="assets_h/js/custom.js"></script>


</body>

</html>