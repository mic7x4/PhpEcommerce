<?php
    require_once('core/init.php');
    include('includes/head.php');
    include('includes/navigation.php');

    $sql = "SELECT * FROM products WHERE featured = 1";
    $featured = $db->query($sql);
?>
    <div class="row">
        <!--left side-->
   <?php include('includes/leftbar.php');?>
        <!--Main COntent-->
    <div class="col-md-8">
    <h2 class="text-center">Featured Products</h2><hr>
        <div class="row">
        <?php while($product = mysqli_fetch_assoc($featured) ) : ?>
            <div class="col-md-3">
                <h4 class="text-center"><?= $product['title']?></h4>
                <img src="<?= $product['image']?>" width="200px" height="200px" alt="<?= $product['title']?>">
                <p class="list-price text-danger">List Price: <s>Frw <?= $product['price']?></s></p>
                <p class="price" >Our price: Frw <?= $product['list_price']?></p>
                <button type="button" class="btn btn-sm btn-success btn-block" onclick="detailsmodal(<?=$product['id'];?>);">Details</button>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
        <!--Side Bar-->
        
    <?php 
        include('includes/rightbar.php');
        include('includes/footer.php');
    ?>
