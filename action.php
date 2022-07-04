<?php
session_start();
include('Config.php');
	// Add products into the cart table
	if (isset($_POST['pid'])) {
        $pid = $_POST['pid'];
        $pname = $_POST['pname'];
        $pprice = $_POST['pprice'];
        $pimage = $_POST['pimage'];
        $pcode = $_POST['pcode'];
        $pqty = $_POST['pqty'];
        $total_price = $pprice * $pqty;

        $stmt = $conn->prepare('SELECT product_code FROM cart WHERE product_code=?');
        $stmt->bind_param('s',$pcode);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = $res->fetch_assoc();
        $code = $r['product_code'] ?? '';
if (!$code) {
	    $query = $conn->prepare('INSERT INTO cart (product_name,product_price,product_image,qty,total_price,product_code) VALUES (?,?,?,?,?,?)');
	    $query->bind_param('ssssss',$pname,$pprice,$pimage,$pqty,$total_price,$pcode);
	    $query->execute();

	    echo '<div class="alert alert-success alert-dismissible mt-2">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Item added to your cart!</strong>
						</div>';
	  } else {
	    echo '<div class="alert alert-danger alert-dismissible mt-2">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Item already added to your cart!</strong>
						</div>';
	  }
	};

    // this is for cart count in the cart box
     if (isset($_GET['cartItem']) && $_GET['cartItem'] == 'cart_items') {
        
        $stmt = $conn->prepare('select * from cart');
        $stmt->execute();
        $stmt->store_result(); // this is for total count of products
        $rowcount = $stmt->num_rows();
        echo $rowcount;
      }

      // single cart item delete here
       if (isset($_GET['remove'])) {
        $removeid = $_GET['remove'];
          $stmt = $conn->prepare('delete from cart where id=?');
          $stmt->bind_param('i',$removeid);
          $stmt->execute();
          $_SESSION['msg-css-value'] = "block";
          $_SESSION['msg'] = 'Item removed from the cart!';
          header('location:cart.php');

        }

        // all cart item delete together
         if (isset($_GET['clear']) and $_GET['clear'] == 'all') {
            $stmt = $conn->prepare('delete from cart');
            $stmt->execute();
            $_SESSION['msg-css-value'] = 'block';
            $_SESSION['msg'] = 'Item removed from the cart!';
          header('location:cart.php');
          }
        


          if (isset($_POST['pdqty'])) {
            $qty = $_POST['pdqty'];
            $pid = $_POST['pdid'];
            $pprice = $_POST['pdprice'];
            $quantityprice =  $pprice*$qty;
            $stmt = $conn->prepare("UPDATE cart set qty=?,total_price=? where id=?");
            $stmt->bind_param('isi',$qty,$quantityprice,$pid);
            $stmt->execute();

            // $tprice = $qty * $pprice;
            // $stmt = $conn->prepare('UPDATE cart SET qty=?, total_price=? WHERE id=?');
            // $stmt->bind_param('isi',$qty,$tprice,$pid);
            // $stmt->execute();

          }
          
?>