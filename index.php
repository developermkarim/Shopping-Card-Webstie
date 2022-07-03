<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="author" content="Sahil Kumar">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Shopping Cart System</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body>
  <!-- Navbar start -->
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="index.php"><i class="fas fa-mobile-alt"></i>&nbsp;&nbsp;Mobile Store</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php"><i class="fas fa-mobile-alt mr-2"></i>Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fas fa-th-list mr-2"></i>Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="checkout.php"><i class="fas fa-money-check-alt mr-2"></i>Checkout</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Product Show here with card system of bootstrap -->

  <div class="container">
    <div id="message"></div>
    <div class="row mt-2 pb-3">
        <?php
        include('Config.php');
        $stmt = $conn->prepare('select * from product');
        $execute = $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
        ?>
          <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
        <div class="card-deck">
          <div class="card p-2 border-secondary mb-2">
            <!-- This Image read from database but not from image directory -->
            <img src="<?= $row['product_image'];?>" alt="Product Image" height="250px">
            <div class="card-body p-1">
              <h4 class="card-title text-center text-info"><?= $row['product_name'] ?></h4>
              <h5 class="card-text text-center text-danger"><span style="font-size: 30px;font-weight: 700;">৳</span> <?= number_format($row['product_price'],2) ?>  /- </h5>

            </div>
            <!-- Card Footer part -->
            <div class="row p-2">
                <form action="" method="POST">

               
                  <div class="col-md-6 py-1 pl-4">
                    <b>Quantity : </b>
                  </div>
                  <div class="col-md-6">
                <input type="number" class="form-control pqty" value="<?= $row['product_qty']?>">
                  </div>
                  <div>
                    <input type="hidden" name="pid" <?= $row['id']?>>
                    <input type="hidden" name="pname" <?= $row['product_name']?>>
                    <input type="hidden" name="pprice" <?= $row['product_price']?>>
                    <input type="hidden" name="pimage" <?= $row['product_image']?>>
                    <input type="hidden" name="pcode" <?= $row['product_code']?>>
                    <button class="btn btn-info btn-block addItemBtn"> <i class="fas fa-cart-plus"></i> &nbsp;&nbsp;Add to
                  cart</button>
                  </div>
                  </form>
                  </div>
        </div>
        </div>  
        </div>  

        <!-- While loop finish here -->
        <?php } ?>
    </div>
    </div>
 </body>
</html>