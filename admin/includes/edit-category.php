<?php

require "dbh.php";

if(isset($_POST['edit-category-btn'])) {

    $name = $_POST['edit-category-name'];
    $metaTitle = $_POST['edit-category-meta-title'];
    $categoryPath = $_POST['edit-category-path'];
    $id = $_POST['category-id'];


    $sql = "UPDATE blog_category SET
    category_title = :name,
    category_meta_title = :category_meta_title,
    category_path = :category_path
    WHERE category_id = :id";
    $smt = $dba->prepare($sql);
   
    $smt->bindParam(':name',$name);
    $smt->bindParam(':category_meta_title',$metaTitle);
    $smt->bindParam(':category_path',$categoryPath);
    $smt->bindParam(':id',$id);
    
    $smt->execute();

    if ($smt) {
        header("Location: ../blog-category.php?editcategory=success");
        exit();
    }
    else {
        header("Location: ../blog-category.php?editcategory=error");
        exit();
    }

}
else {
    header("Location: ../index.php");
    exit();
}