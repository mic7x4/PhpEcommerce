<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
    include 'includes/head.php';
    include 'includes/navigation.php';
    $sql = "SELECT * FROM `categories` WHERE `parent` = 0 ";
    $result = $db->query($sql);
    $errors = array();
    $category = '';
    $post_parent = '';
    
    //Edit Category
    if(isset($_GET['edit']) && !empty($_GET['edit'])){
        $edit_id = (int)$_GET['edit'];
        $edit_id = sanitize($edit_id);
        $edit_sql = "SELECT * FROM categories WHERE id='$edit_id'";
        $edit_result = $db->query($edit_sql);
        $edit_category = mysqli_fetch_assoc($edit_result);
    }

    //Delete Category
    if(isset($_GET['delete']) && !empty($_GET['delete'])){
        $delete_id = (int)$_GET['delete'];
        $delete_id = sanitize($delete_id);

        //Trying to remove the Child in Database 
        $sql = "SELECT * FROM `categories` WHERE `id`='$delete_id'";
        $result = $db->query($sql);
        $category = mysqli_fetch_assoc($result);
        if($category['parent'] == 0){
            $sql = "DELETE FROM `categories` WHERE `parent`='$delete_id'";
            $db->query($sql);
        }
        $dsql = "DELETE FROM `categories` WHERE `id`='$delete_id'";
        $db->query($dsql);
        header('Location:categories.php');
    }

    // Process Form
    if(isset($_POST) && !empty($_POST)){
        $post_parent = sanitize($_POST['parent']);
        $category = sanitize($_POST['category']);
        $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
        if(isset($_GET['edit'])){
            $id = $edit_category['id'];
            $sqlform = "SELECT * FROM categories WHERE category='$category' AND parent = '$post_parent' AND id != '$id'";
        }
        $fresult  = $db->query($sqlform);
        $count = mysqli_num_rows($fresult);
        //if category is blank
        if($category == ''){
            $errors[] .="Category cannot be left blank...";
        }
    // if is exists in database
    if($count > 0){
        $errors[] .= $category." already exists. Please a new one";
    }
    //Display Errors or Update Database
    if(!empty($errors)){
        //display errors 
        $display = display_errors($errors); ?>

        <script>
            jQuery('document').ready(function(){
                jQuery('#errors').html('<?= $display; ?>');
            })
        </script>

    <?php }else{
        //Update Database
        $updatesql = "INSERT INTO categories (category,parent) VALUES ('$category','$post_parent')";
        if(isset($_GET['edit'])){
            $updatesql = "UPDATE categories SET category='$category',parent='$post_parent'  WHERE id = '$edit_id'";
            
        }
        $db->query($updatesql);
        header('Location:categories.php');
    }


    }

    $category_value = '';
    $parent_value = 0;
    if(isset($_GET['edit'])){
        $category_value = $edit_category['category'];
        $parent_value = $edit_category['parent'];
    }else{
        if(isset($_POST)){
            $category_value = $category;
            $parent_value = $post_parent;
        }
    }
?>
<h2 class="text-center">Categories</h2><hr>
<div class="container-fluid">
<div class="row">

    <!-- Form div -->
    <div class="col-md-6">
        <legend class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add A ');?>Category</legend><hr>
        <di id="errors"></di>
        <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
            <div class="form-group">
                <label for="parent">Parent</label>
                <select name="parent" id="parent" class="form-control">
                    <option value="0" <?= (($parent_value == 0)?' selected=selected':'') ;?>>Parent</option>
                    <?php while($parent = mysqli_fetch_assoc($result)): ?>
                        <option value="<?= $parent['id']?>" <?= (($parent_value == $parent['id'])?' selected=selected':'') ;?>><?= $parent['category'];?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" name="category" id="category" value="<?= $category_value ;?>">
            </div>
            <div class="form-group">
                <input type="submit"  value="<?= ((isset($_GET['edit']))?'Edit':'Add ')  ;?> Category" class="btn btn-primary btn-block">
            </div>
        </form>
    </div>


    <!-- Category Div -->
    <div class="col-md-6">
        <table class="table table-bordered">
            <thead>
                <th>Category</th><th>Parent</th><th></th>
            </thead>
            <tbody>
            <?php
                $sql = "SELECT * FROM `categories` WHERE `parent` = 0";
                $result = $db->query($sql);
                while($parent = mysqli_fetch_assoc($result)):
                $parent_id = (int)$parent['id'];
                $sql2 = "SELECT * FROM `categories` WHERE `parent` = '$parent_id'";
                $cresult = $db->query($sql2);
                ?>
                <tr class="bg-success">
                    <td><strong><?=$parent['category'];?></strong></td>
                    <td><strong>Parent</strong></td>
                    <td>
                        <a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-primary">Edit</a>
                        <a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php while($child = mysqli_fetch_assoc($cresult)):?><!--Start of the Child Table Row-->
                <tr class="bg-secondary">
                    <td class="text-white"><?=$child['category'];?></td>
                    <td class="text-white"><?=$parent['category'];?></td>
                    <td>
                        <a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-primary">Edit</a>
                        <a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?><!--End of the Child Table Row-->
            <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>
</div>

<?php include('includes/footer.php'); ?>