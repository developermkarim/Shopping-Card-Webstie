<?php
session_start();
include('Config.php');
	// Add products into the cart table
	if (isset($_POST['pid'])){
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
        


          if (isset($_POST['pd_qty'])) {
            $qty = $_POST['pd_qty'];
            $pid = $_POST['pd_id'];
            $pprice = $_POST['pd_price'];
            $totalprice =  $qty * $pprice;
            // $totalprice = number_format($totalprice,2);
            $stmt = $conn->prepare("UPDATE cart set qty=?,total_price=? where id=?");
            $stmt->bind_param('isi',$qty,$totalprice,$pid);
            $stmt->execute();
          }

           if (isset($_POST['action']) && $_POST['action'] == 'order') {
    // this post value collect from main form element descendant by calling data: $('form).seriallise()
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $price_mode = $_POST['pmode'];
            $delivery_charge = $_POST['charge'];
            $products = $_POST['products']; // this taken from hidden input
            $total_paid = $_POST['grand_total']; // this also taken form hidden input of grand total;
            $data = '';
            $vate = $total_paid*(5/100);
            $stmt = $conn->prepare("INSERT INTO orders(name,email,phone,address,pmode,products,delivery_charge,amount_paid) VALUES(?,?,?,?,?,?,?,?)");
            $stmt->bind_param('ssssssss',$name,$email,$phone,$address,$price_mode,$products,$delivery_charge,$total_paid);
            $stmt->execute();
            $stmt2 = $conn->prepare("DELETE FROM cart");
            $stmt2->execute();
              $data .= '<div class="text-center">
            <h1 class="display-4 mt-2 text-danger">Thank You!</h1>
            <h2 class="text-success">Your Order Placed Successfully!</h2>
            <h4 class="bg-danger text-light rounded p-2">Items Purchased : ' . $products . '</h4>
            <h4>Your Name : ' . $name . '</h4>
            <h4>Your E-mail : ' . $email . '</h4>
            <h4>Your Phone : ' . $phone . '</h4>
            <h4>Total Amount of Products : ' . number_format($total_paid,2) . '</h4>
            <h4> Delivery Charge: '.number_format($delivery_charge,2) .' </h4>
            <h4> Vate: '.number_format($vate,2) .' </h4>
            <h4>All Payment (including vate) : ' .number_format(($total_paid + $delivery_charge+$vate),2)  . '</h4>
          </div>';
          echo $data;

          
          echo $button = '<button onClick="window.print()">Print this page</button>';
            }

?>