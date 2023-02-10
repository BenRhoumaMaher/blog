<?php

require "dbh.php";

if(isset($_POST['delete-category-btn'])) {

    $id = $_POST['category-id'];

    $sql = "DELETE FROM blog_category 
    WHERE category_id = :id";
    $smt = $dba->prepare($sql);
   
    $smt->bindParam(':id',$id);
    
    $smt->execute();

    if ($smt) {
        header("Location: ../blog-category.php?deletecategory=success");
        exit();
    }
    else {
        header("Location: ../blog-category.php?deletecategory=error");
        exit();
    }

}
else {
    header("Location: ../index.php");
    exit();
}