<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: homepage.php");
    exit();
}

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Document</title>
    <style>
        #body {
            background-image: url('Download%205.jpg'); 
            background-repeat: no-repeat;
            background-size: cover;
            text-align: center;
            padding: 350px;
            margin: auto;
            width: 85%;
        }

        #caour {
            background-color: white;
        }

        .nav-link {
            font-size: larger;
        }

        .header {
            padding-top: 1%;
            margin-bottom: 0;
        }

        h1 {
            margin-bottom: 0;
        }

        .navbar {
            padding-top: 0;
            margin-top: -10px;
        }

        .nav-item {
            margin: 0 30px;
        }

        .navbar-nav {
            flex-direction: row;
            display: flex;
            justify-content: center;
            margin: 0 10px;
        }

        .logo {
            height: auto;
            max-height: 75px; /* Set a maximum height for the logo */
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: transparent;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .navbar-collapse .nav-link {
                color: black;
                padding: 10px 0;
            }

            .nav-item {
                margin-bottom: 10px;
            }

            .navbar-nav {
                margin-left: 0%;
            }
        }

        .carouseltitle {
            font-size: 350%;
            font-weight: 500;
            font-family: 'Times New Roman', Times, serif;
            color: #49271D;
        }

        .card-title {
            font-weight: 700;
            text-align: center;
        }

        .card-text {
            font-family: "Lora", serif;
            font-weight: 500;
        }

        .img-wrapper {
            max-width: 100%;
            height: 17rem;
            align-items: flex-start;
            display: flex;
            justify-content: center;
        }

        img {
            max-width: 100%;
            max-height: 100%;
        }

        @media screen and (min-width: 918px) {
            .carousel-inner {
                display: flex;
            }

            .carousel-item {
                display: block;
                margin-right: 0;
                flex: 0 0 calc(100%/3);
            }
        }

        .carousel-inner {
            padding: 1rem;
        }

        .card {
            margin: 0 .5rem;
            align-items: center;
            display: flex;
            justify-content: center;
            border: #49271D 2px solid;
            border-radius: 10px;
        }

        #carouselbtn {
            background-color: #49271D;
        }

    </style>

</head>
<body>
    <div class="container">
        <h1 class="text-center mt-3 fw-light">Naurs</h1>
        <nav class="navbar navbar-expand-md align-items-left justify-content-left" style="margin-top:10px">
            <a class="navbar-brand" href="homepage.php" style="margin-left: 15px;">
                <img src="logo.png" alt="Logo" class="logo">
            </a>
            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-label="Expand Navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <div class="container d-flex flex-column align-items-left">
                    <ul class="navbar-nav d-flex align-items-center justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link" href="homepage.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="product.php">Products</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="about.html">About Us</a>
                        </li>
                        <?php if ($isLoggedIn): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="?action=logout">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container-fluid" id="body">
        <h1>Welcome!</h1>
        <P>Very demure, very mindful</P>
        <a href="product.php" class="btn btn-outline-dark">SHOP NOW!</a>
    </div>

       <!-- CAROUSEL START -->
       <div class="container" id="caour">
        <p class="carouseltitle align-items-center d-flex justify-content-center my-4 " id="carouseltitle">Best Seller</p>
        <p class="d-flex justify-content-center fs-5 fw-light" >These our Best Seller Products</p>
        <div id="carouselExampleDark" class="carousel mx-4" data-bs-ride="carousel">
            <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
            <div class="carousel-inner">
                <div class="carousel-item active ">
                    <div class="card align-items-center justify-content-center bg-transparent" >
                        <div class="img-wrapper border-1 ">
                            <img src="pic1.jpg"  alt="...">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title d-flex align-items-center justify-content-center">Color Corrector</h3>
                            <p class="card-text d-flex align-items-center justify-content-center mx-3" style="text-align:center;">lm;dajiWL</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" >
                    <div class="card bg-transparent" >
                        <div class="img-wrapper">
                            <img src="pic2.jpg"  alt="...">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title d-flex align-items-center justify-content-center">Soft Matte Concealer</h3>
                            <p class="card-text d-flex align-items-center justify-content-center mx-3" style="text-align:center;">uhqsuhdhlkja</p>
                        </div>
                    </div>
                
                </div>
                <div class="carousel-item">
                    <div class="card bg-transparent" >
                        <div class="img-wrapper">
                            <img src="pic4.jpg"  alt="...">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title d-flex align-items-center justify-content-center">Explicit Lipstick</h3>
                            <p class="card-text d-flex align-items-center justify-content-center mx-3" style="text-align:center;">gykyfhy </p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card bg-transparent" >
                        <div class="img-wrapper">
                            <img src="pic5.jpg"  alt="...">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title d-flex align-items-center justify-content-center">Blush Quad</h3>
                            <p class="card-text d-flex align-items-center justify-content-center mx-3" style="text-align:center;">gkjygdkjasgkd</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card bg-transparent" >
                        <div class="img-wrapper">
                            <img src="pic6.jpg"  alt="...">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title d-flex align-items-center justify-content-center">Lip Balm</h3>
                            <p class="card-text d-flex align-items-center justify-content-center mx-3" style="text-align:center;">kghdjgha </p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev btn btn-light" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next btn btn-light" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
        <!-- CAROUSEL END -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var carouselWidth = $('.carousel-inner')[0].scrollWidth;
        var cardWidth = $('.carousel-item').width();
        var scrollPosition = 0;
        $('.carousel-control-next').on('click', function(){
            if(scrollPosition < (carouselWidth - (cardWidth * 4))){
                console.log('next');
                scrollPosition = scrollPosition + cardWidth;
                $('.carousel-inner').animate({scrollLeft: scrollPosition}, 600);
            }
        });
        $('.carousel-control-prev').on('click', function(){
            if(scrollPosition > 0){
                console.log('prev');
                scrollPosition = scrollPosition - cardWidth;
                $('.carousel-inner').animate({scrollLeft: scrollPosition}, 600);
            }
        });
    </script>
</body>
</html>
