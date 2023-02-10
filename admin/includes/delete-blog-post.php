<?php

require "dbh.php";

if(isset($_POST['delete-blog-post-btn'])) {

    $id = $_POST['blog-post-id'];

    $sql = "Update blog_post set post_status = '2' 
    WHERE blog_post_id = :id";
    $smt = $dba->prepare($sql);
   
    $smt->bindParam(':id',$id);
    
    $smt->execute();

    if ($smt) {
        header("Location: ../blogs.php?deleteblogpost=success");
        exit();
    }
    else {
        header("Location: ../blogs.php?deleteblogpost=error");
        exit();
    }

}
else {
    header("Location: ../index.php");
    exit();
}