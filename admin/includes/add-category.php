<?php

require "dbh.php";

if(isset($_POST['add-category-btn'])) {

    $name = $_POST['category-name'];
    $metaTitle = $_POST['category-meta-title'];
    $categoryPath = $_POST['category-path'];

    $date = date("Y-m-d");
    $time = date("H:i:s");

    $sql = "INSERT INTO blog_category(category_title,category_meta_title,category_path,date_created,time_created)
    VALUES(:name,:metaTitle,:categoryPath,:date,:time)";
    $smt = $dba->prepare($sql);
    $smt->bindParam(':name',$name);
    $smt->bindParam(':metaTitle',$metaTitle);
    $smt->bindParam(':categoryPath',$categoryPath);
    $smt->bindParam(':date',$date);
    $smt->bindParam(':time',$time);
    $smt->execute();

    if ($smt) {
        header("Location: ../blog-category.php?addcategory=success");
        exit();
    }
    else {
        header("Location: ../blog-category.php?addcategory=error");
        exit();
    }

}
else {
    header("Location: ../index.php");
    exit();
}