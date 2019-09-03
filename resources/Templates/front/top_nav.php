<link href="css/styles.css" rel="stylesheet">

<div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">
            <img src="https://i.imgur.com/kaBX8fL.png" alt="">
        </a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li>
                <a href="shop.php?lowPrice&highPrice">Shop</a>
            </li>
            <li>
                <a href="login.php">Login</a>
            </li>
            <li>
                <a href="admin">Admin</a>
            </li>
            <li>
                <a href="checkout.php">Checkout</a>
            </li>
            <li>
                <a href="contact.php">Contact</a>
            </li>
            <li>
                <a href="register.php">Register</a>
            </li>

        </ul>

            <form class="search" action="search_page.php" method="get">
                <input type="text" placeholder="Product Search" name="search">
                <button type="submit">Search</button>
            </form>




    </div>

</div>

<!-- /.navbar-collapse -->
</div>
<!-- /.container -->
