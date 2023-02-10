<?php

require "dbh.php";
session_start();

if(isset($_POST['submit-edit-blog'])) {
    $blogid = $_POST['blog-id'];
    $title = $_POST['blog-title'];
    $metaTitle = $_POST['blog-meta-title'];
    $blogCategoryId = $_POST['blog-category'];
    $blogSummary = $_POST['blog-summary'];
    $blogContent = $_POST['blog-content'];
    $blogTags = $_POST['blog-tags'];
    $blogPath = $_POST['blog-path'];
    $homePagePlacement = $_POST['blog-home-page-placement'];

    $date = date("Y-m-d");
    $time = date("H:i:s");

    if(empty($title)) {
        formError("emptytitle");
    }
    else if(empty($blogCategoryId)) {
        formError("emptycategory");
    }
    else if(empty($blogSummary)) {
        formError("emptysummary");
    }
    else if(empty($blogContent)) {
        formError("emptycontent");
    }
    else if(empty($blogTags)) {
        formError("emptytags");
    }
    else if(empty($blogPath)) {
        formError("emptypath");
    }
    if(strpos($blogPath, " ") !== false ){
        formError("pathcontainsspaces");
    }
    if(empty($homePagePlacement)) {
        $homePagePlacement = 0;
    }

    $sq = "SELECT post_title FROM blog_post where post_title = '$title'  
    and post_title != '$title' and post_status != '2'";
    $sm = $dba->prepare($sq);
    $sm->execute();
    $qr = $sm->rowCount();

    $sqq = "SELECT post_path FROM blog_post where post_path = '$blogPath' 
    and post_path != '$blogPath' and post_status != '2'";
    $smm = $dba->prepare($sqq);
    $smm->execute();
    $qrr = $smm->rowCount();

    if($qr > 0) {
        formError("titlebeingused"); 
    }
    if($qrr > 0) {
        formError("pathbeingused"); 
    }
    if($homePagePlacement !=0) {
        $sqa = "SELECT * from blog_post where home_page_placement = '$homePagePlacement'  And post_status != '2'";
        $sma = $dba->prepare($sqa);
        $sma->execute();
        $qra = $sma->rowCount();
        if($qra > 0) {
            $slup = "UPDATE blog_post set home_page_placement = '0' where home_page_placement = '$homePagePlacement'
            And post_status != '2'"; 
            $smt = $dba->prepare($slup);
            $smt->execute();
            if(!$smt){
                formError("homepageplacementerror");
            }
        }
    }
    
    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main","main_image_url");
    $altImgUrl = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt","alt_image_url");
    
    if($mainImgUrl == "noupdate") {
        if($altImgUrl == "noupdate") {
            $sql = "UPDATE blog_post SET 
            category_id = :blogCategoryId,
            post_title = :title,
            post_meta_title = :metaTitle,
            post_path = :blogPath,
            post_summary = :blogSummary,
            post_content = :blogContent,
            home_page_placement = :homePagePlacement,
            date_updated = :date,
            time_updated = :time
            WHERE blog_post_id = :id";
    
            $smt = $dba->prepare($sql);
            $smt->bindParam(':blogCategoryId',$blogCategoryId);
            $smt->bindParam(':title',$title);
            $smt->bindParam(':metaTitle',$metaTitle);
            $smt->bindParam(':blogPath',$blogPath);
            $smt->bindParam(':blogSummary',$blogSummary);
            $smt->bindParam(':blogContent',$blogContent);
            $smt->bindParam(':homePagePlacement',$homePagePlacement);
            $smt->bindParam(':date',$date);
            $smt->bindParam(':time',$time); 
            $smt->bindParam(':id',$blogid); 
            $smt->execute();
        } else {
            $sql = "UPDATE blog_post SET 
            category_id = :blogCategoryId,
            post_title = :title,
            post_meta_title = :metaTitle,
            post_path = :blogPath,
            post_summary = :blogSummary,
            post_content = :blogContent,
            alt_image_url = :altImgUrl,
            home_page_placement = :homePagePlacement,
            date_updated = :date,
            time_updated = :time
            WHERE blog_post_id = :id";
    
            $smt = $dba->prepare($sql);
            $smt->bindParam(':blogCategoryId',$blogCategoryId);
            $smt->bindParam(':title',$title);
            $smt->bindParam(':metaTitle',$metaTitle);
            $smt->bindParam(':blogPath',$blogPath);
            $smt->bindParam(':blogSummary',$blogSummary);
            $smt->bindParam(':blogContent',$blogContent);
            $smt->bindParam(':altImgUrl',$altImgUrl);
            $smt->bindParam(':homePagePlacement',$homePagePlacement);
            $smt->bindParam(':date',$date);
            $smt->bindParam(':time',$time); 
            $smt->bindParam(':id',$blogid); 
            $smt->execute();
        }
    }
    else if($altImgUrl == "noupdate") {
        if($mainImgUrl != "noupdate"){
            $sql = "UPDATE blog_post SET 
            category_id = :blogCategoryId,
            post_title = :title,
            post_meta_title = :metaTitle,
            post_path = :blogPath,
            post_summary = :blogSummary,
            post_content = :blogContent,
            main_image_url = :mainImgUrl,
            home_page_placement = :homePagePlacement,
            date_updated = :date,
            time_updated = :time
            WHERE blog_post_id = :id";
    
            $smt = $dba->prepare($sql);
            $smt->bindParam(':blogCategoryId',$blogCategoryId);
            $smt->bindParam(':title',$title);
            $smt->bindParam(':metaTitle',$metaTitle);
            $smt->bindParam(':blogPath',$blogPath);
            $smt->bindParam(':blogSummary',$blogSummary);
            $smt->bindParam(':blogContent',$blogContent);
            $smt->bindParam(':mainImgUrl',$mainImgUrl);
            $smt->bindParam(':homePagePlacement',$homePagePlacement);
            $smt->bindParam(':date',$date);
            $smt->bindParam(':time',$time); 
            $smt->bindParam(':id',$blogid); 
            $smt->execute();
        }
    }
    else {
        $sql = "UPDATE blog_post SET 
        category_id = :blogCategoryId,
        post_title = :title,
        post_meta_title = :metaTitle,
        post_path = :blogPath,
        post_summary = :blogSummary,
        post_content = :blogContent,
        main_image_url = :mainImgUrl,
        alt_image_url = :altImgUrl,
        home_page_placement = :homePagePlacement,
        date_updated = :date,
        time_updated = :time
        WHERE blog_post_id = :id";

        $smt = $dba->prepare($sql);
        $smt->bindParam(':blogCategoryId',$blogCategoryId);
        $smt->bindParam(':title',$title);
        $smt->bindParam(':metaTitle',$metaTitle);
        $smt->bindParam(':blogPath',$blogPath);
        $smt->bindParam(':blogSummary',$blogSummary);
        $smt->bindParam(':blogContent',$blogContent);
        $smt->bindParam(':mainImgUrl',$mainImgUrl);
        $smt->bindParam(':altImgUrl',$altImgUrl);
        $smt->bindParam(':homePagePlacement',$homePagePlacement);
        $smt->bindParam(':date',$date);
        $smt->bindParam(':time',$time); 
        $smt->bindParam(':id',$blogid); 
        $smt->execute();
    }

    $sqlUpdateBlogTags = "UPDATE blog_tags SET tag = '$blogTags' WHERE blog_post_id = :blogid";
    $smta = $dba->prepare($sqlUpdateBlogTags);
    $smta->bindParam(':blogid',$blogid);
    $smta->execute();

    if ($smt && $smta) {
        formSuccess();
    }
    else {
        formError("sqlerror");
    }

}
else {
    header("Location: ../index.php");
    exit();
}
function formSuccess() {
    require "dbh.php";
    unset($_SESSION['editTitle']);
    unset($_SESSION['editMetaTitle']);
    unset($_SESSION['editCategoryId']);
    unset($_SESSION['editSummary']);
    unset($_SESSION['editContent']);
    unset($_SESSION['editTags']); 
    unset($_SESSION['editPath']); 
    unset($_SESSION['editHomePagePlacement']); 

    header("Location: ../blogs.php?updateblog=success");
    exit();
}
function formError($errorCode){

    require "dbh.php";
    $_SESSION['editTitle'] = $_POST['blog-title'];
    $_SESSION['editMetaTitle'] = $_POST['blog-meta-title'];
    $_SESSION['editCategoryId'] = $_POST['blog-category'];
    $_SESSION['editSummary'] = $_POST['blog-summary'];
    $_SESSION['editContent'] = $_POST['blog-content'];
    $_SESSION['editTags'] = $_POST['blog-tags'];
    $_SESSION['editPath'] = $_POST['blog-path'];
    $_SESSION['editHomePagePlacement'] = $_POST['blog-home-page-placement'];
    
    header("Location: ../edit-blog.php?updateblog=".$errorCode);
    exit();
} 
// uploading the images
function uploadImage($img,$imgName,$imgType, $imgDbColumn) {
    require "dbh.php";
    $imgUrl = "";
    $validExt = array("jpg", "jpeg", "png", "bmp", "gif");
    if($img == "") {
        return "noupdate";
    }
    else {
        if ($_FILES[$imgName]["size"] <= 0) {
            formError($imgType . "imageerror");
        }
        else {
            // end() returns the last element of the arrays
            $ext = strtolower(end(explode(".", $img)));
            if(!in_array($ext,$validExt)) {
                formError("invalidType".$imgType. "image");
            }

            // delete the old image
            $blogid = $_POST['blog-id'];
            $sqlold = "SELECT ".$imgDbColumn." FROM blog_post where blog_post_id = :blogid";
            $smtold = $dba->prepare($sqlold);
            $smtold->bindParam(':blogid',$blogid);
            $smtold->execute();
            $rowold = $smtold->fetch();
            
            if($rowold){
                $oldImgUrl = $rowold[$imgDbColumn];
            }

            if(!empty($oldImgUrl)){
                $oldImgUrlArray = explode("/",$oldImgUrl);
                $oldImgName = end($oldImgUrlArray);
                $oldImgPath = "../images/".$oldImgName;
                unlink($oldImgPath);
            }
            // add the new image
            $folder = "../images/blog-images";
            $imageNewName = rand(10000, 990000) . '_' . time() . '.' . $ext;
            $imgPath = $folder . $imageNewName;
    
            if(move_uploaded_file($_FILES[$imgName]['tmp_name'],$imgPath)) {
                $imgUrl = "http://localhost/blog/admin/images/blog-images" . $imageNewName;
            }
            else {
                formError("errorUploading".$imgType."image");
            }
        }
        return $imgUrl;
    }
    
}