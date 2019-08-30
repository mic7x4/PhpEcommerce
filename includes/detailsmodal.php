<?php 
    require_once('../core/init.php');
    $id = $_POST['id'];
    $id = (int)$id;
    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = $db->query($sql);
    $product = mysqli_fetch_assoc($result);
    $brand_id = $product['brand'];
    $sql = "SELECT brand FROM brand WHERE id = '$brand_id'";
    $brand_query = $db->query($sql);
    $brand = mysqli_fetch_assoc($brand_query);
    $sizestring = $product['sizes'];
    $sizestring = rtrim($sizestring,',');
    $size_array = explode(',',$sizestring);

?>

<?php ob_start(); ?>
<!-- Details Modal -->
<div class="modal fade" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"  id="exampleModalLabel"><?= $product['title'];?></h5>
        <button type="button" class="close" onclick="closeModal();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="center-block">
                    <img src="<?= $product['image'];?>" width="50%" height="300px"   alt="<?=$product['title'];?>" class="details img-responsive">
                    </div>
                </div>
                <div class="col-sm-6">
                    <h4 class="text-center">Details</h4>
                    <p><?= nl2br($product['description']);?></p><hr>
                    <p>Price: Rwf <?= $product['price'];?></p>
                    <p>Brand: <?= $brand['brand']; ?></p>
                        <form action="add_cart.php" method="post">
                        <div class="form-group">
                            <div class="col-xs-3">
                                <label for="quantity">Quantity:</label>
                                <input type="text" class="form-control" name="quantity" id="quantity">
                            </div>
                            <!-- <p>Available : 3</p> -->
                            <div class="form-group">
                                <label for="size">Size:</label>
                                <select class="form-control" id="size" name="size">
                                    <option value=""></option>
                                    <?php
                                        foreach($size_array as $string){
                                            $string_array = explode(':',$string);
                                            $size = $string_array[0];
                                            $quantity = $string_array[1];
                                            echo ' <option value="'.$size.'">'.$size.'( '.$quantity.' Available )</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        </form>
                </div>
            </div>
        </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal();">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
    </div>
    </div>
    </div>
</div>
<script>
    function closeModal(){
        jQuery("#details-modal").modal('hide');
        setTimeout(function(){
            jQuery('#details-modal').remove();
            jQuery('.modal-backdrop').remove();
        },500);
    }
</script>
<?php echo ob_get_clean();  ?>