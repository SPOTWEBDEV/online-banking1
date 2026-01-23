<?php
include('./server/connection.php');
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta name="description" content="<?php echo $sitename; ?>">
    <meta name="keywords" content="banking, savings, transfers, investment, virtual card">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $sitename ?></title>
    <link rel="icon" href="images/IMG_09099.png" type="image/png" sizes="16x16">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" media="all" />
    <!-- animate css -->
    <link rel="stylesheet" href="assets/css/animate.min.css" type="text/css" media="all" />
    <!-- owl carousel css -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css" type="text/css" media="all" />
    <!-- meanmenu css -->
    <link rel="stylesheet" href="assets/css/meanmenu.min.css" type="text/css" media="all" />
    <!-- magnific popup css -->
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css" type="text/css" media="all" />

    <!-- ✅ ICONS THAT WILL WORK 100% (Font Awesome via CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- style css -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" media="all" />
    <!-- responsive css -->
    <link rel="stylesheet" href="assets/css/responsive.css" type="text/css" media="all" />
    <!-- theme dark css -->
    <link rel="stylesheet" href="assets/css/theme-dark.css" type="text/css" media="all" />
</head>

<body>
    <!-- preloader -->
    <div class="preloader orange-gradient">
        <div class="preloader-wrapper">
            <div class="preloader-grid">
                <div class="preloader-grid-item preloader-grid-item-1"></div>
                <div class="preloader-grid-item preloader-grid-item-2"></div>
                <div class="preloader-grid-item preloader-grid-item-3"></div>
                <div class="preloader-grid-item preloader-grid-item-4"></div>
                <div class="preloader-grid-item preloader-grid-item-5"></div>
                <div class="preloader-grid-item preloader-grid-item-6"></div>
                <div class="preloader-grid-item preloader-grid-item-7"></div>
                <div class="preloader-grid-item preloader-grid-item-8"></div>
                <div class="preloader-grid-item preloader-grid-item-9"></div>
            </div>
        </div>
    </div>
    <!-- .end preloader -->

    <!-- navbar -->
    <div class="fixed-top">
        <div class="navbar-area">
            <!-- mobile menu -->
            <div class="mobile-nav">
                <a href="./" class="logo">
                    <img src="images/IMG_09099.png" class="logo1" alt="logo">
                    <img src="images/IMG_09099.png" class="logo2" alt="logo">
                </a>

                <div class="navbar-option">
                    <div class="navbar-option-item">
                        <!-- ✅ ALL BUTTONS -> ./auth/sign_up/ -->
                        <a href="./auth/sign_up/">
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- desktop menu -->
            <div class="main-nav main-nav-2">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-md navbar-light">
                        <a class="navbar-brand" href="./">
                            <img src="images/IMG_09099.png" class="logo1" alt="logo">
                        </a>

                        <!-- navbar option -->
                        <div class="navbar-option">
                            <div class="navbar-option-item">
                                <!-- ✅ ALL BUTTONS -> ./auth/sign_up/ -->
                                <a href="./auth/sign_up/" class="btn1 btn-with-image text-nowrap">
                                    <i class="fa-solid fa-user-plus"></i>
                                    Get Started
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!--end navbar-->

    <!-- header -->
    <header class="header header-bg-2">
        <div class="header-shape-2">
            <div class="header-shape-2-item">
                <img src="assets/images/header-2-shape.png" alt="shape">
            </div>
        </div>

        <div class="container-fluid">
            <div class="header-inner">
                <div class="row align-items-center justify-content-center">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <div class="header-content-2">
                            <h1>Bank smarter. Move money instantly. Grow daily.</h1>
                            <p>
                                Open your account in minutes, send and receive payments fast, track your balance in real-time,
                                and build wealth with simple investment plans — all from one secure dashboard.
                            </p>

                            <ul class="section-button">
                                <li>
                                   
                                    <a href="./auth/sign_up/" class="btn1 orange-gradient btn-with-image">
                                        <i class="fa-solid fa-bolt"></i>
                                        Sign up
                                    </a>
                                </li>
                                <li>
                                  
                                    <a href="./auth/sign_in/" class="btn1 btn-with-image blue-gradient">
                                        <i class="fa-solid fa-shield-halved"></i>
                                        Login
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <div class="header-content-2-img">
                            <img src="assets/images/imac.png" alt="imac">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- .end header -->

    <!-- home-logo-section-2 -->
    <div class="home-logo-section-2 border-top-mob">
        <div class="container">
            <div class="section-title">
                <p>Trusted by customers who value speed, safety, and simplicity.</p>
            </div>
            <div class="home-logo-content mt-30">
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-1.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-2.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-3.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-4.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-5.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-6.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-7.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-8.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-9.png" alt="logo"></a></div>
                <div class="home-logo-item"><a href="#"><img src="assets/images/logo-10.png" alt="logo"></a></div>
            </div>
        </div>
    </div>
    <!-- .end home-logo-section-2 -->

    <!-- home-about-section-2 -->
    <section class="home-about-section-2 bg-off-white pt-100 pb-70 overflow-hidden">
        <div class="container-fluid p-0">
            <div class="home-about-content">

                <!-- enterprise -->
                <div class="row align-items-center m-0">
                    <div class="col-sm-12 col-md-12 col-lg-6 p-0">
                        <div class="home-facility-overview desk-ml-auto pr-20 pl-20 pb-30">
                            <h3 class="home-about-title">Built for serious money movement</h3>
                            <p class="home-about-para">
                                Send local and international transfers, manage multiple wallets, and view transactions clearly —
                                with bank-grade security from day one.
                            </p>
                            <div class="home-about-list">
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Instant transfers & receipts</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Savings & balance tracking</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Fraud checks & secure login</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Fast deposit approvals</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Clear transaction history</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> 24/7 account access</div>
                            </div>
                            <div class="home-about-animation">
                                <div class="home-animation-item"><img src="assets/images/curve-line.png" alt="animated-icon"></div>
                                <div class="home-animation-item"><img src="assets/images/triangle.png" alt="animated-icon"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 p-0">
                        <div class="home-facility-item img-right-res pb-30">
                            <img src="assets/images/home-enterprise-2.png" alt="facility">
                        </div>
                    </div>
                </div>

                <div class="section-mtb-40"></div>

                <!-- business -->
                <div class="row align-items-center m-0">
                    <div class="col-sm-12 col-md-12 col-lg-6 p-0">
                        <div class="home-facility-item img-left-res pb-30">
                            <img src="assets/images/home-business-2.png" alt="facility">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 p-0">
                        <div class="home-facility-overview desk-mr-auto pr-20 pl-20 pb-30">
                            <h3 class="home-about-title">For personal money and business cashflow</h3>
                            <p class="home-about-para">
                                Track income, control spending, and keep your accounts organized —
                                whether you’re building a business or managing daily life.
                            </p>
                            <div class="home-about-list">
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Deposit and withdraw with ease</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Smart limits & account controls</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Wallets for balance, loan & crypto</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Verified payouts and transfers</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Real-time status updates</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Fast support when you need it</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-mtb-40"></div>

                <!-- entrepreneur -->
                <div class="row align-items-center m-0">
                    <div class="col-sm-12 col-md-12 col-lg-6 p-0">
                        <div class="home-facility-overview desk-ml-auto pr-20 pl-20 pb-30">
                            <h3 class="home-about-title">Earn with simple investment plans</h3>
                            <p class="home-about-para">
                                Choose a plan, invest any amount, and watch your returns grow.
                                Everything is transparent: duration, daily profit, and total profit.
                            </p>
                            <div class="home-about-list">
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Clear plan duration</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Daily profit calculation</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Investment history tracking</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Secure balance handling</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Simple dashboard view</div>
                                <div class="home-about-list-item"><img src="assets/images/check.png" alt="check"> Withdraw earnings anytime</div>
                            </div>
                            <div class="home-about-animation entrepreneur-animation">
                                <div class="home-animation-item"><img src="assets/images/curve-line.png" alt="animated-icon"></div>
                                <div class="home-animation-item"><img src="assets/images/triangle.png" alt="animated-icon"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 p-0">
                        <div class="home-facility-item img-right-res pb-30">
                            <img src="assets/images/home-entreprenour-2.png" alt="facility">
                           
                          
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- counter-section -->
    <section class="counter-section pt-100 pb-70">
        <div class="container">
            <div class="section-title">
                <h2>Fast. Secure. Designed for daily finance.</h2>
            </div>
            <div class="counter-content">
                <div class="counter-item">
                    <h3><span class="counter">70+</span></span></h3>
                    <p>Total Users</p>
                </div>
                <div class="counter-item">
                    <h3><span class="counter">40k</span></h3>
                    <p>Daily Transactions</p>
                    <div class="counter-loader"><span></span><span></span><span></span></div>
                </div>
                <div class="counter-item">
                    <h3><spa class="counter">1000k</span></h3>
                    <p>Countries</p>
                    <div class="counter-loader"><span></span><span></span><span></span></div>
                </div>
                <div class="counter-item">
                    <h3><span class="counter">590+</span></span></h3>
                    <p>Employess</p>
                    <div class="counter-loader"><span></span><span></span><span></span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- home-facility-section -->
    <section class="home-facility-section pt-100 pb-70 border-top-mob">
        <div class="container">
            <div class="home-facility-content">
                <div class="row align-items-center justify-content-center">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <div class="home-facility-item pb-30">
                            <div class="home-facility-details">
                                <div class="home-service-start">
                                    <h2>Open an account today and take control of your money</h2>
                                    <p>
                                        Fund your wallet, make transfers, track transactions, and start investing —
                                        all inside one clean dashboard.
                                    </p>
                                    <p>
                                        No long forms. No stress. Just fast banking designed for real life.
                                    </p>

                                    <!-- ✅ ALL BUTTONS -> ./auth/sign_up/ -->
                                    <a href="./auth/sign_up/" class="btn1 blue-gradient btn-with-image">
                                        <i class="fa-solid fa-user-plus"></i>
                                        Get Started
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <div class="home-facility-item-2 home-image-content pb-30">
                            <img src="assets/images/home-facility-bg-2.png" alt="facility" class="scale-one-zero-six">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- home-quick-contact-section -->
    <section class="home-quick-contact-section blue-gradient">
        <div class="container-fluid">
            <div class="home-quick-contact home-quick-contact-2">
                <div class="logo-bg-icon">
                    <div class="logo-bg-item"><img src="assets/images/circle.png" alt="icon"></div>
                    <div class="logo-bg-item"><img src="assets/images/square.png" alt="icon"></div>
                </div>
                <div class="quick-contact-inner">
                    <h2>Ready to start? Create your account in minutes.</h2>
                    <p>Secure signup. Real-time wallet. Clear investment history.</p>
                    <ul class="section-button">
                        <li>
                            <a href="./auth/sign_up/" class="btn1 orange-gradient btn-with-image">
                                <i class="fa-solid fa-bolt"></i>
                                Register
                            </a>
                        </li>
                        <li>
                            <a href="./auth/sign_in/" class="btn1 btn-with-image">
                                <i class="fa-solid fa-lock"></i>
                                Login
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <footer class="footer-bg">
        <div class="container">
            <div class="footer-upper">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="footer-content-item">
                            <div class="footer-logo">
                                <a href="./"><img src="images/IMG_09099.png" alt="logo"></a>
                            </div>
                            <div class="footer-details">
                                <p>
                                    <?php echo $sitename; ?> is built for fast payments, secure transfers, and simple investment tracking —
                                    so you always know where your money is going.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg-2">
                        <div class="footer-content-list footer-content-item">
                            <div class="footer-content-title">
                                <h3>Quick Links</h3>
                            </div>
                            <ul class="footer-details footer-list">
                                <li><a href="./auth/sign_up/">Register</a></li>
                                <li><a href="./auth/sign_in/">Login</a></li>
                             
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg-2">
                        <div class="footer-content-list footer-content-item">
                            <div class="footer-content-title">
                                <h3>Features</h3>
                            </div>
                            <ul class="footer-details footer-list">
                               <p>Register to get Started with many Features</p>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="footer-content-list footer-content-item">
                            <div class="footer-content-title">
                                <h3>Security Note</h3>
                            </div>
                            <ul class="footer-details footer-list">
                                <li> <span>Secure sessions & hashed passwords</span></li>
                                <li> <span>Transaction status tracking</span></li>
                                <li><span>Help available inside your dashboard</span></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="footer-lower">
                <div class="footer-lower-item footer-copyright-text">
                    <div class="copy-right-text text-end">
                        <p>© <script>document.write(new Date().getFullYear())</script> <?php echo $sitename; ?>. All rights reserved.</p>
                    </div>
                </div>

                <div class="footer-lower-item footer-social-logo">
                    <ul class="footer-social-list">
                        <li class="social-btn social-btn-fb"><a href="./auth/sign_up/"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li class="social-btn social-btn-tw"><a href="./auth/sign_up/"><i class="fa-brands fa-x-twitter"></i></a></li>
                        <li class="social-btn social-btn-ins"><a href="./auth/sign_up/"><i class="fa-brands fa-instagram"></i></a></li>
                        <li class="social-btn social-btn-pin"><a href="./auth/sign_up/"><i class="fa-brands fa-pinterest-p"></i></a></li>
                        <li class="social-btn social-btn-yt"><a href="./auth/sign_up/"><i class="fa-brands fa-youtube"></i></a></li>
                    </ul>
                </div>
            </div>

        </div>
    </footer>
    <!-- .end footer -->

    <!-- essential js -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- magnific popup js -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- owl carousel js -->
    <script src="assets/js/owl.carousel.min.js"></script>
    <!-- form ajazchimp js -->
    <script src="assets/js/jquery.ajaxchimp.min.js"></script>
    <!-- form validator js  -->
    <script src="assets/js/form-validator.min.js"></script>
    <!-- contact form js -->
    <script src="assets/js/contact-form-script.js"></script>
    <!-- meanmenu js -->
    <script src="assets/js/jquery.meanmenu.min.js"></script>
    <!-- waypoints js -->
    <script src="assets/js/jquery.waypoints.js"></script>
    <!-- counter js -->
    <script src="assets/js/counter-up.js"></script>
    <!-- main js -->
    <script src="assets/js/script.js"></script>
</body>

</html>
