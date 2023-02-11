<?php
require "includes/dbh.php";
include "includes/unset-sessions.php";

// get all the posts except for the deleted ones
$sql = "SELECT * from blog_post where post_status != '2'";
$smt = $dba->prepare($sql);
$smt->execute();
$num = $smt->fetchAll(PDO::FETCH_OBJ);
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
</head>

<body>
    <div id="wrapper">
        <?php include "header.php"; include "sidebar.php"; ?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Blog Posts
                        </h1>
                    </div>
                </div>
                <?php 
                if (isset($_REQUEST['addblog'])) {
                    if ($_REQUEST['addblog'] == "success"){
                        echo "<div class='alert alert-success'><strong>Success!</strong> Blog Added!</div>";
                    }
                }

                if (isset($_REQUEST['updateblog'])) {
                    if ($_REQUEST['updateblog'] == "success"){
                        echo "<div class='alert alert-success'><strong>Success!</strong> Blog saved!</div>";
                    }
                }

                if (isset($_REQUEST['deleteblogpost'])) {
                    if ($_REQUEST['deleteblogpost'] == "success"){
                        echo "<div class='alert alert-success'><strong>Success!</strong> Blog Post Deleted!</div>";
                    }
                    else if ($_REQUEST['deleteblogpost'] == "error"){
                        echo "<div class='alert alert-danger'><strong>Error!</strong> Blog Post was not Deleted!</div>";
                    }
                }
                ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        All Blogs
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Views</th>
                                        <th>Blog Path</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                                $counter = 0;
                                                 foreach($num as $numblog) 
                                                 {
                                                    $counter++;
                                                    $id = $numblog->blog_post_id;
                                                    $name = $numblog->post_title;
                                                    $cid = $numblog->category_id;
                                                    $views = $numblog->blog_post_views;
                                                    $blogPath = $numblog->post_path;
                                                    
                                                    $sqla = "SELECT category_title from blog_category where category_id = :id";
                                                    $smtt = $dba->prepare($sqla);
                                                    $smtt->bindParam(':id', $cid);
                                                    $smtt->execute();
                                                    $nums = $smtt->fetch();
                                                    if($nums) {
                                                    $categoryName = $nums['category_title'];
                                                    }
                                                    ?>
                                    <tr>
                                        <td><?= $id; ?></td>
                                        <td><?= $name; ?></td>
                                        <td><?= $categoryName; ?></td>
                                        <td><?= $views; ?></td>
                                        <td><?= $blogPath; ?></td>
                                        <td>
                                            <button class="popup-button"
                                                onclick="window.open('../single-blog.php?blog=<?php echo $blogPath; ?>', '_blank');">View</button>
                                            <button class="popup-button"
                                                onclick="location.href='edit-blog.php?blogid=<?php echo $id; ?>'">Edit</button>
                                            <button class="pupup-button" data-toggle="modal"
                                                data-target="#delete<?php echo $id; ?>">Delete</button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="delete<?php echo $id; ?>" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="includes/delete-blog-post.php" method="POST">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel">Delete
                                                            Blog Post
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="blog-post-id"
                                                            value="<?php echo $id; ?>" />
                                                        <p>Are you sure that you want to delete this
                                                            blog post ?</p>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary"
                                                            name="delete-blog-post-btn">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                                 }
                                                 ?>

                                </tbody>
                            </table>
                            <button class="popup-button"
                                onclick="location.href='includes/download-blog.php'">Download</button>
                        </div>
                    </div>
                </div>
                <!-- End  Kitchen Sink -->
            </div>
            <!-- /.col-lg-12 -->
        </div>


        <!-- /. ROW  -->
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
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>


</body>

</html>