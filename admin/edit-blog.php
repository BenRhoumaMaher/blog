<?php
include "includes/dbh.php";
session_start();

if (isset($_REQUEST['blogid'])) {
    
    $blogid = $_REQUEST['blogid'];
    
    if(empty($blogid)){
        header("Location: blogs.php");
        exit();
    }
    $_SESSION['editblogid'] = $_REQUEST['blogid'];

    $sql = "SELECT * FROM blog_post WHERE blog_post_id = :blogid";
    $smt = $dba->prepare($sql);
    $smt->bindParam(':blogid',$blogid);
    $smt->execute();
    $qu = $smt->fetch();
    
    if($qu) {
        $_SESSION['editTitle'] = $qu['post_title'];
        $_SESSION['editMetaTitle'] = $qu['post_meta_title'];
        $_SESSION['editCategoryId'] = $qu['category_id'];
        $_SESSION['editSummary'] = $qu['post_summary'];
        $_SESSION['editContent'] = $qu['post_content'];
        $_SESSION['editPath'] = $qu['post_path'];
        $_SESSION['editHomePagePlacement'] = $qu['home_page_placement'];
    }
    else {
        header("Location: blogs.php");
        exit();
    }
    $sqll = "SELECT * FROM blog_tags WHERE blog_post_id = :blogid";
    $smtt = $dba->prepare($sqll);
    $smtt->bindParam(':blogid',$blogid);
    $smtt->execute();
    $quu = $smtt->fetch();
    if($quu) {
        $_SESSION['editTags'] = $quu['tag'];
    }
}
else if (isset($_SESSION['editblogid'])){}
else {
    header("Location: blogs.php");
    exit();
}
$ql = "select * from blog_post where blog_post_id = '".$_SESSION['editblogid']."'";
$smtw = $dba->prepare($ql);
$smtw->execute();
$quw = $smtw->fetch();
    if($quw) {
    $mainImgUrl = $quw['main_image_url'];
    $altImgUrl = $quw['alt_image_url'];
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Free Bootstrap Admin Template : Dream</title>
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- Summernote API-->
    <link href='summernote/summernote.min.css' rel='stylesheet' type='text/css' />
</head>

<body>
    <div id="wrapper">
        <?php include "header.php"; include "sidebar.php"; ?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Edit Blog Post
                        </h1>
                    </div>
                </div>
                <?php 
                 
                 if(isset($_REQUEST['updateblog'])) {
                    if($_REQUEST['updateblog'] == "emptytitle") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please add a blog title.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "emptycategory") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please select a blog category.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "emptysummary") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please select a blog summary.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "emptycontent") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please add a blog content.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "emptytags") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please add some blog tags.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "emptypath") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please add a blog path.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "sqlerror") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please try again.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "pathcontainsspaces") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please do not add any spaces in the blog path.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "emptymainimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please upload a main image.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "emptyaltimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please upload an alternate image.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "mainimageerror") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please upload another main image.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "altimageerror") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please upload another alternate image.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "invalidtypemainimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Main Image -> Upload only jpg,jpeg, gif, bmp images.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "invalidtypealtimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Alt Image -> Upload only jpg,jpeg, gif, bmp images.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "erroruploadingmainimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Main Image -> There was an error while uploading. Please try again.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "erroruploadingaltimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Alt Image -> There was an error while uploading. Please try again.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "titlebeingused") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> The title is being used in another blog. Try picking a different title.
                        </div>";
                    }
                    else if($_REQUEST['updateblog'] == "homepageplacement") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> An unexpected error occured while trying to set the home page placement.
                        </div>";
                    }
                 }

                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Edit: <?php echo $_SESSION['editTitle']; ?>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form role="form" method="post" action="includes/update-blog.php"
                                            enctype="multipart/form-data">
                                            <input type="hidden" name="blog-id" value="<?php echo $blogid; ?>" />
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input class="form-control" name="blog-title" value="<?php 
                                                    echo $_SESSION['editTitle']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Meta Title</label>
                                                <input class="form-control" name="blog-meta-title" value="<?php 
                                                    echo $_SESSION['editMetaTitle']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Blog Category</label>
                                                <select class="form-control" name="blog-category">
                                                    <option value="" selected hidden disabled>Select a category</option>
                                                    <?php

                                                    $sql = "select * from blog_category";
                                                    $smt = $dba->prepare($sql);
                                                    $smt->execute();
                                                    $cat = $smt->fetchAll(PDO::FETCH_OBJ);
                                                    foreach($cat as $cg) {
                                                        $cid = $cg->category_id;
                                                        $cName = $cg->category_title;
                                                            if($_SESSION['editCategoryId'] == $cid) {
                                                                echo "<option value='".$cid."' selected=''>".$cName."</option>";

                                                            }
                                                            else {
                                                                echo "<option value='".$cid."'>".$cName."</option>";
                                                            }
                                                    }
                                                    
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Update Main Image</label>
                                                <input type="file" name="main-blog-image" id="main-blog-image">
                                                <?php
                                                    if(!empty($mainImgUrl)){
                                                    echo "<p style='font-size:inherit;'><a href='' data-toggle='modal'
                                                    data-target='#main-image' class='popup-button' style='margin-top:10px'
                                                    >View Existing Image</a></p>";
                                                    }
                                                ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Update Alternate Image</label>
                                                <input type="file" name="alt-blog-image" id="alt-blog-image">
                                                <?php
                                                    if(!empty($altImgUrl)){
                                                    echo "<p style='font-size:inherit;'><a href='' data-toggle='modal'
                                                    data-target='#alt-image' class='popup-button' style='margin-top:10px'
                                                    >View Existing Image</a></p>";
                                                    }
                                                ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Summary</label>
                                                <textarea class="form-control" rows="3" name="blog-summary"><?php
                                                    echo $_SESSION['editSummary']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Blog Content</label>
                                                <textarea class="form-control" rows="3" name="blog-content"
                                                    id="summernote"><?php
                                                    echo $_SESSION['editContent']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Blog Tags (separated by comma)</label>
                                                <input class="form-control" name="blog-tags" value="<?php
                                                    echo $_SESSION['editTags']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Blog Path</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">www.maher_blog.com/</span>
                                                    <input type="text" class="form-control" placeholder=""
                                                        name="blog-path" value="<?php
                                                    echo $_SESSION['editPath']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Home Page Placement</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement"
                                                        id="optionsRadiosInline1" value="1" <?php if (isset($_SESSION['editHomePagePlacement'])) {
                                                            if($_SESSION['editHomePagePlacement'] == 1) {
                                                                echo "checked=''";
                                                            }
                                                        } ?>>1
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement"
                                                        id="optionsRadiosInline2" value="2" <?php if (isset($_SESSION['editHomePagePlacement'])) {
                                                            if($_SESSION['editHomePagePlacement'] == 2) {
                                                                echo "checked=''";
                                                            }
                                                        } ?>>2
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement"
                                                        id="optionsRadiosInline3" value="3" <?php if (isset($_SESSION['editHomePagePlacement'])) {
                                                            if($_SESSION['editHomePagePlacement'] == 3) {
                                                                echo "checked=''";
                                                            }
                                                        } ?>>3
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-default" name="submit-edit-blog">Save
                                                changes</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.row (nested) -->
                                <?php 
                                    if (!empty($mainImgUrl)){
                                        ?>
                                <div class="modal fade" id="main-image" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Main Image
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?php echo $mainImgUrl; ?>"
                                                    style="max-width:100%; height:auto;" />
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    }
                                    ?>

                                <?php 
                                    if (!empty($altImgUrl)){
                                        ?>
                                <div class="modal fade" id="alt-image" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Alt Image
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?php echo $altImgUrl; ?>"
                                                    style="max-width:100%; height:auto;" />
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    }
                                ?>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <?php include "footer.php"; ?>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>
    <!-- Summernote Js -->
    <script src="summernote/summernote.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            minHeight: null,
            maxHeight: null,
            focus: false
        });
    });
    </script>

</body>

</html>