<?php
    $sql = "SELECT * FROM categories WHERE parent = 0";
    $pquery = $db->query($sql);
    
?>
<nav class="navbar navbar-expand-lg  navbar-light bg-light">
    <div class="container">
        <a href="index.php" class="navbar-brand">CrookzBoutique</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <!-- Looping into the parent categories -->
                <?php while($parent = mysqli_fetch_assoc($pquery)): ?>
                    <?php 
                        $parent_id = $parent["id"]; 
                        $sql2 = "SELECT * FROM categories WHERE parent='$parent_id'";
                        $cquery = $db->query($sql2); 
                    ?>
                <!-- Menu items -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$parent["category"];?></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php while($child = mysqli_fetch_assoc($cquery)):?>
                        <a class="dropdown-item" href="#"><?=$child['category']; ?></a>
                    <?php endwhile;?>
                    </div>
                </li>
                <?php endwhile; ?>
                </nav>
            </ul>
        </div>
    </div>
