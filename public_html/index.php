<?php
require_once("../resources/config.php");
include(TEMPLATE_FRONT . DS . "header.php")
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- /.col-lg-3 -->
        <div class="container" style="text-align: center">
            <div class="row" style="display: inline-block">
                <img style="display: block; margin: 0 auto; padding:10px width: 10%" class="logo" src="resources/uploads/welcome1.png" alt="Welcome">
            </div>
            <div class="row">
                <h1 class="m">Shop Featured Products</h1>
            </div>


            <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">

                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <a   href="item.php?id=48">
                            <img style="display: block; margin-left: auto; margin-right: auto; width: 30%;" class="d-block img-fluid" src="resources/uploads/dell.png" alt="First slide">
                            Dell XPS 15 15.6" 4K Ultra HD Laptop
                        </a>
                    </div>
                    <div class="carousel-item">
                        <a   href="item.php?id=54">
                            <img style="display: block; margin-left: auto; margin-right: auto; width: 30%;" class="d-block img-fluid" src="resources/uploads/mac2.png" alt="Second slide">
                            Apple Macbook Air
                        </a>
                    </div>
                    <div class="carousel-item">
                        <a   href="item.php?id=46">
                            <img style="display: block; margin-left: auto; margin-right: auto; width: 30%;" class="d-block img-fluid" src="resources/uploads/surface.png" alt="Third slide">
                            Microsoft Surface Studio 2
                        </a>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>


            <div class="row">
                <?php
    if(isset($_SESSION['useraccount']) && $_SESSION['useraccount'][1] == 'buyer'){
        include(TEMPLATE_BACK . "/recommended.php");
    }?>


            </div>
            <!-- /.row -->

        </div>
        <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

</div>
<!-- /.container -->

<!-- Footer -->
<?php    include(TEMPLATE_FRONT . DS . "footer.php")?>
