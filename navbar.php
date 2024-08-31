<style>
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
</style>


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
                            <a class="nav-link" href="#">Shopping Cart</a>
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