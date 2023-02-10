<?php
include "includes/dbh.php";
session_start();
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
                            Write a Blog
                        </h1>
                    </div>
                </div>
                <?php 
                 
                 if(isset($_REQUEST['addblog'])) {
                    if($_REQUEST['addblog'] == "emptytitle") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please add a blog title.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "emptycategory") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please select a blog category.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "emptysummary") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please select a blog summary.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "emptycontent") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please add a blog content.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "emptytags") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please add some blog tags.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "emptypath") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please add a blog path.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "sqlerror") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please try again.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "pathcontainsspaces") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please do not add any spaces in the blog path.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "emptymainimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please upload a main image.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "emptyaltimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please upload an alternate image.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "mainimageerror") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please upload another main image.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "altimageerror") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Please upload another alternate image.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "invalidtypemainimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Main Image -> Upload only jpg,jpeg, gif, bmp images.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "invalidtypealtimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Alt Image -> Upload only jpg,jpeg, gif, bmp images.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "erroruploadingmainimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Main Image -> There was an error while uploading. Please try again.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "erroruploadingaltimage") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> Alt Image -> There was an error while uploading. Please try again.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "titlebeingused") {
                        echo "<div class='alert alert-danger'>
                        <strong>Error!</strong> The title is being used in another blog. Try picking a different title.
                        </div>";
                    }
                    else if($_REQUEST['addblog'] == "homepageplacement") {
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
                                Write a Blog
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form role="form" method="post" action="includes/add-blog.php"
                                            enctype="multipart/form-data" onsubmit="return validateImage();">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input class="form-control" name="blog-title" value="<?php if (isset($_SESSION['blogTitle'])) {
                                                    echo $_SESSION['blogTitle'];} ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Meta Title</label>
                                                <input class="form-control" name="blog-meta-title" value="<?php if (isset($_SESSION['blogMetaTitle'])) {
                                                    echo $_SESSION['blogMetaTitle'];} ?>">
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
                                                        if(isset($_SESSION['blogCategoryId'])){
                                                            if($_SESSION['blogCategoryId'] == $cid) {
                                                                echo "<option value='".$cid."' selected=''>".$cName."</option>";

                                                            }
                                                            else {
                                                                echo "<option value='".$cid."'>".$cName."</option>";
                                                            }
                                                        }
                                                        else {
                                                            echo "<option value='".$cid."'>".$cName."</option>";
                                                        }
                                                    }
                                                    
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Main Image</label>
                                                <input type="file" name="main-blog-image" id="main-blog-image">
                                            </div>
                                            <div class="form-group">
                                                <label>Alternate Image</label>
                                                <input type="file" name="alt-blog-image" id="alt-blog-image">
                                            </div>
                                            <div class="form-group">
                                                <label>Summary</label>
                                                <textarea class="form-control" rows="3" name="blog-summary"><?php if (isset($_SESSION['blogSummary'])) {
                                                    echo $_SESSION['blogSummary'];} ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Blog Content</label>
                                                <textarea class="form-control" rows="3" name="blog-content"
                                                    id="summernote"><?php if (isset($_SESSION['blogContent'])) {
                                                    echo $_SESSION['blogContent'];} ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Blog Tags (separated by comma)</label>
                                                <input class="form-control" name="blog-tags" value="<?php if (isset($_SESSION['blogTags'])) {
                                                    echo $_SESSION['blogTags'];} ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Blog Path</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">www.maher_blog.com/</span>
                                                    <input type="text" class="form-control" placeholder=""
                                                        name="blog-path" value="<?php if (isset($_SESSION['blogPath'])) {
                                                    echo $_SESSION['blogPath'];} ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Home Page Placement</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement"
                                                        id="optionsRadiosInline1" value="1" <?php if (isset($_SESSION['blogHomePagePlacement'])) {
                                                            if($_SESSION['blogHomePagePlacement'] == 1) {
                                                                echo "checked=''";
                                                            }
                                                        } ?>>1
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement"
                                                        id="optionsRadiosInline2" value="2" <?php if (isset($_SESSION['blogHomePagePlacement'])) {
                                                            if($_SESSION['blogHomePagePlacement'] == 2) {
                                                                echo "checked=''";
                                                            }
                                                        } ?>>2
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement"
                                                        id="optionsRadiosInline3" value="3" <?php if (isset($_SESSION['blogHomePagePlacement'])) {
                                                            if($_SESSION['blogHomePagePlacement'] == 3) {
                                                                echo "checked=''";
                                                            }
                                                        } ?>>3
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-default"
                                                name="submit-blog">Add</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.row (nested) -->
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
    <script>
    // checks if the user inputs an image
    function validateImage() {
        // get the images by their Id
        var main_img = $("#main-blog-image").val();
        var alt_img = $("#alt-blog-image").val();
        // store the acceptable extensions inside an Array
        var exts = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        // get the extension of the image
        var get_ext_main_img = main_img.split('.');
        var get_ext_alt_img = alt_img.split('.');

        get_ext_main_img = get_ext_main_img.reverse();
        get_ext_alt_img = get_ext_alt_img.reverse();
        //initialize checking variables 
        main_image_check = false;
        alt_image_check = false;
        //check if the use puts an image
        if (main_img.length > 0) {
            //check if the extension of the puted image is acceptable
            if ($.inArray(get_ext_main_img[0].toLowerCase(), exts) > -1) {
                main_image_check = true;
            } else {
                alert("Error -> Main Image. Upload only jpg,jpeg,png,gif,bmp images.");
                main_image_check = false;
            }
        } else {
            alert("Please upload a main image.");
            main_image_check = false;
        }
        if (alt_img.length > 0) {
            //check if the extension of the puted image is acceptable
            if ($.inArray(get_ext_alt_img[0].toLowerCase(), exts) > -1) {
                alt_image_check = true;
            } else {
                alert("Error -> Alternate Image. Upload only jpg,jpeg,png,gif,bmp images.");
                alt_image_check = false;
            }
        } else {
            alert("Please upload a alternate image.");
            alt_image_check = false;
        }
        if (main_image_check == true && alt_image_check == true) {
            return true;
        } else {
            return false;
        }
    }
    </script>

</body>

</html>