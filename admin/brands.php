<?php
    require_once('../core/init.php');
    include("includes/head.php");
    include('includes/navigation.php');
    //Get Brand from Database 
    $sql = "SELECT * FROM brand ORDER BY brand";
    $results  = $db->query($sql);
    $errors = array();

    //Edit Brand 
    if(isset($_GET['edit'])&& !empty(isset($_GET['edit']))){
        $edit_id = (int)$_GET['edit'];
        $edit_id = sanitize($edit_id);
        $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
        $edit_result = $db->query($sql2);
        $eBrand = mysqli_fetch_assoc($edit_result);
    }
    //Delete Brand 
    if(isset($_GET['delete']) && !empty(isset($_GET['delete']))){
        $delete_id = (int)$_GET['delete'];
        $delete_id = sanitize($delete_id);
        $sql = "DELETE FROM brand WHERE id='$delete_id'";
        $db->query($sql);
        header('Location:brands.php');
    }


    //If Add Form is Submitted 
    if(isset($_POST['add_submit'])){
        $brand = sanitize($_POST['brand']);
        //Check if brand is Blank 
        if($brand == ''){
            $errors[] .="You must Enter a brand ";
        }
        //Check if Brand exists in database
        $sql = "SELECT * FROM brand WHERE brand = '$brand'";
        if(isset($_GET['edit'])){
            $sql = "SELECT * FROM brand WHERE brand='$brand' AND id != '$edit_id'";
        }
        $result = $db->query($sql);
        $count = mysqli_num_rows($result);
        if($count > 0 ){
            $errors[] .= $brand.' Already Exist.! Please Choose another Brand name';
        }
        //Display Errors 
        if(!empty($errors)){
            echo display_errors($errors);
        }else{
            //Add Brand to the database 
            $sql = "INSERT INTO brand  (brand) VALUES ('$brand')";
            if(isset($_GET['edit'])){
                $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
            }
            $db->query($sql);
            header('Location:brands.php');
        }
    }

?>
<div class="container-fluid">
<h2 class="text-center">Brands</h2><hr>
<!-- Brands form -->
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <form action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" class="form-inline" method="post"> 
        <div class="form-group">
        <?php 
            $brand_value = '';
            if(isset($_GET['edit'])){
            $brand_value = $eBrand['brand'];
        }else{
            if(isset($_POST['brand'])){
                $brand_value = sanitize($_POST['brand']);
            }
        }
        ?>
            <label for="brand"><strong><?=((isset($_GET['edit']))?'Edit':'Add a') ?> Brand </strong>: </label>
            <input type="text" class="form-control" name="brand" id="brand" value="<?= $brand_value;?>">
            <?php if(isset($_GET['edit'])): ?>
                &nbsp;<a href="brands.php" class="btn btn-danger">Cancel</a>&nbsp;
            <?php endif;?>
            &nbsp;<input type="submit" class="btn  btn-success" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add') ?> Brand">
        </div>
    </form>
        </div>
        <div class="col md-4"></div>
    </div><hr>
    <!-- End of The Brand Form -->
</div>
<table class="table table-bordered table-striped table-condensed" style="width:auto;margin:0 auto;">
    <thead>
        <th></th><th>Brand</th><th></th>
    </thead>
    <tbody>
        <?php while( $brand = mysqli_fetch_assoc($results)):?>
            <tr>
            <td><a href="brands.php?edit=<?= $brand['id'] ?>" class="btn btn-xs btn-primary"><span class="gryphicon gryphicon-pencil">Edit</span></a></td>
            <td><?= $brand['brand'] ?></td>
            <td><a href="brands.php?delete=<?= $brand['id'] ?>" class="btn btn-xs btn-danger"><span class="gryphicon gryphicon-pencil">Delete</span></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

<?php include('includes/footer.php');?>