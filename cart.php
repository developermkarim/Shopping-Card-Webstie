<?php include('common.php'); ?>
<div class="container">
<div class="row justify-content-center">
  
    <div class="table-responsive mt-2">
      <table class="table table-bordered table-striped text-center">
        <thead>
          <tr>
            <td colspan="7">
              <h4 class="text-center text-info m-0">Products in your cart!</h4>
            </td>
          </tr>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>
              <a href="action.php?clear=all" class="badge-danger badge p-1" onclick="return confirm('Are you sure want to clear your cart?');"><i class="fas fa-trash"></i>&nbsp;&nbsp;Clear Cart</a>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            require 'Config.php';
           $stmt = $conn->prepare('SELECT * FROM cart');
            $stmt->execute();
           $res =  $stmt->get_result();
           $grand_total = 0;
           while ($row = $res->fetch_assoc()){ 
          
          ?>
          <tr>
            <!-- We will hidden type the inputs with the values which are need to use in next page. -->
            <td><?= $row['id'] ?></td>
            <input type="hidden" class="pid" value="<?= $row['id'] ?>">
            <td><img src="<?= $row['product_image'] ?>" width="50"></td>
            <td><?= $row['product_name'] ?></td>
            <td>
              <i class="fas fa-rupee-sign"></i>&nbsp;&nbsp;<?= number_format($row['product_price'],2); ?>
            </td>
            <input type="hidden" class="pprice" value="<?= number_format($row['product_price'],2) ?>">
            <td>
              <input type="number" class="form-control itemQty" value="<?= $row['qty'] ?>" style="width:75px;">
            </td>
            <td>
                <i class="fas fa-rupee-sign"></i>&nbsp;&nbsp;<?= number_format($row['total_price'],2) ?></td>
            <td>
              <a href="action.php?remove<?php $row['id']; ?>" class="text-danger lead" onclick="return confirm('Are you sure want to remove this item?');"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
        
         
          <tr>
          <?php  $grand_total+= $row['total_price'] ?>
          <?php } ?>
            <td colspan="3">
              <a href="index.php" class="btn btn-success"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Continue
                Shopping</a>
            </td>
            <td colspan="2"><b>Grand Total</b></td>
           
            <td><b><i class="fas fa-rupee-sign"></i>&nbsp;&nbsp;<?= number_format($grand_total,2); ?></b></td>
            <td>
             <a href="checkout.php" class="btn btn-info <?= ($grand_total > 1) ?'':'disabled' ?>"><i class="far fa-credit-card"></i>&nbsp; Check Out</a>
            </td>
          </tr>
        
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

<script type="text/javascript">
$(document).ready(function(){
    $('.itemQty').change(function(){
        var $el = $(this).closest('tr');
        var price = $el.find('.pprice').val();
        var id = $el.find('.pid').val();
        var quantity = $el.find('.itemQty').val();

        $.ajax({
            url:'action.php',
            method:'post',
            data:{
                pprice:price,
                pqty: quantity,
                ptotal: grand_total
            },
            success:function(response){
                $().html(response);
            }
        })
    })
})
</script>
</body>

</html>
