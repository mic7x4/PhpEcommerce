<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
    include 'includes/head.php';
    include 'includes/navigation.php';

    if(isset($_GET['add']) || isset($_GET['edit']) ){
        $brandSql = $db->query("SELECT * FROM brand ORDER BY brand");    
        $parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

        if(isset($_GET['edit'])){
            $edit_id = (int)$_GET['edit'];
        }

        $sizesArray = array();
        if($_POST){
            $title = sanitize($_POST['title']);
            $brand = sanitize($_POST['brand']);
            $categories = sanitize($_POST['child']);
            $price = sanitize($_POST['price']);
            $list_price = sanitize($_POST['list_price']);
            $sizes = sanitize($_POST['sizes']);
            $description = sanitize($_POST['description']);

            $dbpath = '';
            $errors = array();
            if(!empty($_POST['sizes'])){
                $sizeString = sanitize($_POST['sizes']);
                $sizeString = rtrim($sizeString,',');
                $sizesArray = explode(',',$sizeString);
                $sArray = array();
                $qArray = array();
                foreach($sizesArray as $ss){
                    $s = explode(':',$ss);
                    $sArray[] = $s[0];
                    $qArray[] = $s[1];
                }
            }else{
                $sizesArray = array();
            }
            $required = array('title','brand','price','parent','child','sizes');
            foreach($required as $field){
                if($_POST[$field] == ''){
                    $errors[] = 'All fields with and Asterisks are required!.';
                    break;
                }
            }
            if(!empty($_FILES)){
                var_dump($_FILES);
                $photo = $_FILES['photo'];
                $name = $photo['name'];
                $nameArray = explode('.',$name);
                $fileName = $nameArray[0];
                $fileExt = $nameArray[1];
                $mime = explode('/',$photo['type']);
                $mimeType = $mime[0];
                $mimeExt  = $mime[1];
                $tmpLoc  = $photo['tmp_name'];
                $fileSize = $photo['size'];
                
                $allowed = array('png','gif','jpg','jpeg');
                $uploadName = md5(microtime()).'.'.$fileExt;
                $uploadPath = BASEURL."images/products/".$uploadName;
                $dbpath = "/ecommerce/images/products/".$uploadName;

                echo 'This is the upload path'.$uploadPath.'  DB path'.$dbpath;
                

                if($mimeType != 'image'){
                    $errors[] = "The file must be an image!!";
                }
                if(!in_array($fileExt,$allowed)){
                    $errors[] ="The file extension must be gif,png,jepg or jpg";
                }

                if($fileSize > 10000000){
                    $errors[] = "The file size must be under 15mbs";
                }

                if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
                    $errors[] = "File extension doesn't match the file.";
                }
            }
            
            if(!empty($errors)){
                echo display_errors($errors);
            }else{
                move_uploaded_file($tmpLoc,$uploadPath);
                //Upload file and insert into database
                $insertSql = "INSERT INTO products (`title`,`price`,`list_price`,`brand`,`categories`,`sizes`,`image`,`description`) 
                    VALUES ('$title','$price','$list_price','$brand','$categories','$sizes','$dbpath','$description')";
                    $db->query($insertSql);
                header('Location:products.php');
            }
        }
    ?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':"Add New");?> Product</h2><hr>
    <form action="products.php?add=1" method="POST" enctype="multipart/form-data">
    <div class="row container-fluid">
        <div class="col-md-3 form-group">
            <label for="title">Title*:</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= ((isset($_POST['title']))?sanitize($_POST['title']):'')?>">
        </div>
        <div class="col-md-3 form-group">
            <label for="brand">Brand*</label>
            <select name="brand" id="brand" class="form-control">
                <option value="<?= ((isset($_POST['brand']) && $_POST['brand'] == '')?' selected':'') ;?>"></option>
                <?php while($brand = mysqli_fetch_assoc($brandSql)): ?>
                    <option value="<?=$brand['id'];?>"<?= ((isset($_POST['brand']) && $_POST['brand'] == $brand['id'] )?' selected':'') ?> ><?=$brand['brand'];?></option>
                <?php endwhile;?>
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label for="parent">Parent Category*:</label>
            <select name="parent" id="parent" class="form-control">
                <option value="" <?= ((isset($_POST['parent']) && $_POST['parent'] == '' )?' selected':'') ;?>></option>
                <?php while($parent = mysqli_fetch_assoc($parentQuery)): ?>
                    <option value="<?= $parent['id']; ?>" <?= ((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])?' selected':'') ?> ><?= $parent['category'] ?></option>
                <?php endwhile;?>
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label for="child">Child Category*:</label>
            <select name="child" id="child" class="form-control"></select>
        </div>
        </div>
        <div class="row container-fluid">
            <div class="col-md-3 form-group">
                <label for="price">Price*:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?=((isset($_POST['price']))?sanitize($_POST['price']):'') ?>">
            </div>          
            <div class="col-md-3 form-group">
                <label for="list_price">List Price:</label>
                <input type="text" class="form-control" id="list_price" name="list_price" value="<?=((isset($_POST['list_price']))?sanitize($_POST['list_price']):'') ?>">
            </div> 
            <div class="col-md-3 form-group">
                <label for="quantity">Quantity & Sizes*:</label>
                <button class="btn btn-primary form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Sizes</button>
            </div>
            <div class="col-md-3 form-group">
                <label for="sizes">Sizes & Qty Preview</label>
                <input class="form-control" type="text" name="sizes" id="sizes" value="<?=((isset($_POST['sizes']))?sanitize($_POST['sizes']):'');?>" readonly>
            </div>
        </div>
        <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <label for="photo">Product Photo*:</label>
                <input type="file" name="photo" id="photo" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="6" class="form-control"><?=((isset($_POST['description']))?sanitize($_POST['description']):'')?></textarea>
            </div>
        </div>
        </div><br>
        <div class="row offset-md-10">
            <a href="products.php" class="btn btn-danger mr-2">Cancel</a>
            <input type="submit" name="edit" value="<?=((isset($_GET['edit']))?'Edit':"Add") ?> Product" class="btn btn-primary" >
        </div> 
    </form>
    <!--Add Product Modal-->
<div class="modal fade bs-modal-lg" id="sizesModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="sizesModalLabel">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title text-center" id="sizesModalLabel" >Sizes and Quantity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
                <?php for($i=1;$i<=6; $i++): ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="size<?=$i;?>">Size:</label>
                        <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" class="form-control" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>">
                    </div>
                    <div class="col-md-6">
                        <label for="qty<?=$i;?>">Quantity:</label>
                        <input type="number" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" name="qty" id="qty<?=$i;?>" class="form-control" min="0">
                    </div>
                </div>
                <?php endfor; ?>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-warning" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save Changes</button>
        </div>
    </div> 
    </div>
</div><!-- End of Modal -->


<?php }else{
    $sql = "SELECT * FROM products WHERE deleted = 0 ";
    $presults = $db->query($sql);

    if(isset($_GET['featured'])){
        $id = (int)$_GET['id'];
        $featured = (int)$_GET['featured'];
        $featuredsql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
        $db->query($featuredsql);
        header('Location:products.php');
    }
?>
<h2 class="text-center">Products</h2>
    <div class="container-fluid"><a href="products.php?add=1" class="btn btn-md btn-primary float-sm-right" id="add-product-btn" style="margin-top: -35px;">Add product</a><div class="clearfix"></div></div>
<hr>
<div class="container-fluid">
<table class="table table-bordered table-condensed table-striped">
    <thead>
        <th></th><th>Product</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th>
    </thead>
    <tbody>
        <?php while($product = mysqli_fetch_assoc($presults)): 
                $childID = $product['categories'];
                $catSql = "SELECT * FROM categories WHERE id = '$childID'";
                $result = $db->query($catSql);
                $child = mysqli_fetch_assoc($result);
                $parentID = $child['parent'];
                $pSql = "SELECT * FROM categories WHERE id = '$parentID' ";
                $presult= $db->query($pSql);
                $parent = mysqli_fetch_assoc($presult);
                $category = $parent['category'].' - '.$child['category']; 
            ?>
            <tr>
                <td>
                    <a href="products.php?edit=<?= $product['id'];?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="products.php?delete=<?= $product['id'];?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
                <td><?= $product['title'];?></td>
                <td><?= money($product['price']);?></td>
                <td><?=$category;?></td>
                <td>
                    <a class="btn btn-info btn-sm" href="products.php?featured=<?=(($product['featured']== 0)?'1':'0');?>&id=<?=$product['id'];?>">
                    <?=(($product['featured']==1)?'Unapprove':'Approve') ;?>
                    </a>&nbsp;<?=(($product['featured']==1)?'Featured Product':'');?></td>
                </td>
                <td>0</td>
            </tr>
        <?php endwhile;?>
    </tbody>
</table>
</div>
<?php }include('includes/footer.php'); ?>