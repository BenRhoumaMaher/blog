<?php

require "dbh.php";
session_start();
if(isset($_POST['submit-blog'])) {

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

    $sq = "SELECT post_title FROM blog_post where post_title = '$title' and post_status != '2'";
    $sm = $dba->prepare($sq);
    $sm->execute();
    $qr = $sm->rowCount();

    $sqq = "SELECT post_path FROM blog_post where post_path = '$blogPath' and post_status != '2'";
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
    
    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main");
    $altImgUrl = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt");
    


    $sql = "INSERT INTO blog_post(category_id,post_title,post_meta_title,post_path,post_summary,
    post_content,main_image_url,alt_image_url,home_page_placement,post_status,date_created,time_created)
    VALUES(:blogCategoryId,:title,:metaTitle,:blogPath,:blogSummary,:blogContent,:mainImgUrl,:altImgUrl,:homePagePlacement,'1',:date,:time)";
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
    $smt->execute();

    if ($smt) {

        $blogPostId = $dba->lastInsertId();
        $sqlAddTags = "INSERT INTO blog_tags(blog_post_id,tag) VALUES(:blogpostid,:blogtags)";
        $smtt = $dba->prepare($sqlAddTags);
        $smtt->bindParam(':blogpostid',$blogPostId);
        $smtt->bindParam(':blogtags',$blogTags);
        $smtt->execute();
        
        if($smtt){    
        unset($_SESSION['blogTitle']);
        unset($_SESSION['blogMetaTitle']);
        unset($_SESSION['blogCategoryId']);
        unset($_SESSION['blogSummary']);
        unset($_SESSION['blogContent']);
        unset($_SESSION['blogTags']); 
        unset($_SESSION['blogPath']); 
        unset($_SESSION['blogHomePagePlacement']); 
        header("Location: ../blogs.php?addblog=success");
        exit();
    }
    else {
            formError("sqlerror");
    }
    }
    else {
        formError("sqlerror");
    }

}
else {
    header("Location: ../index.php");
    exit();
}
function formError($errorCode){

    require "dbh.php";
    $_SESSION['blogTitle'] = $_POST['blog-title'];
    $_SESSION['blogMetaTitle'] = $_POST['blog-meta-title'];
    $_SESSION['blogCategoryId'] = $_POST['blog-category'];
    $_SESSION['blogSummary'] = $_POST['blog-summary'];
    $_SESSION['blogContent'] = $_POST['blog-content'];
    $_SESSION['blogTags'] = $_POST['blog-tags'];
    $_SESSION['blogPath'] = $_POST['blog-path'];
    $_SESSION['blogHomePagePlacement'] = $_POST['blog-home-page-placement'];
    
    header("Location: ../write-a-blog.php?addblog=".$errorCode);
    exit();
} 
// uploading the images
function uploadImage($img,$imgName,$imgType) {
    $imgUrl = "";
    $validExt = array("jpg", "jpeg", "png", "bmp", "gif");
    if($img == "") {
        formError("empty" . $imgType . "image");
    }
    else if ($_FILES[$imgName]["size"] <= 0) {
        formError($imgType . "imageerror");
    }
    else {
        // end() returns the last element of the arrays
        $ext = strtolower(end(explode(".", $img)));
        if(!in_array($ext,$validExt)) {
            formError("invalidType".$imgType. "image");
        }
        $folder = "../images/blog-images";
        $imageNewName = rand(10000, 990000) . '_' . time() . '.' . $ext;
        $imgPath = $folder . $imageNewName;

        if(move_uploaded_file($_FILES[$imgName]['tmp_name'],$imgPath)) {
            $imgUrl = "http://localhost/blog/admin/images/blogimages/" . $imageNewName;
        }
        else {
            formError("errorUploading".$imgType."image");
        }
    }
    return $imgUrl;
}