<?php
require "admin/includes/dbh.php";

if(isset($_REQUEST['blog'])) {
    
    $blogpath = $_REQUEST['blog'];

    $sqlgetblog = "SELECT * FROM blog_post WHERE post_path = '$blogpath' AND post_status = '1'";
    $smt = $dba->prepare($sqlgetblog);
    $smt->execute();
    $qa = $smt->fetch();

    if($qa) {
        $blogpostid = $qa['blog_post_id'];
        $blogcategoryid = $qa['category_id'];
        $blogtitle = $qa['post_title'];
        $blogmetatitle = $qa['post_meta_title'];
        $blogcontent = $qa['post_content'];
        $blogmainimgurl = $qa['main_image_url'];
        $blogcreationdate = $qa['date_created'];
    }
    else {
        header("Location: index.php");
        exit();
    }

    $sqlgetcategory = "SELECT * FROM blog_category WHERE category_id = '$blogcategoryid'";
    $smtt = $dba->prepare($sqlgetcategory);
    $smtt->execute();
    $qaa = $smtt->fetch();

    if($qaa) {
        $categorytitle = $qaa['category_title'];
        $blogcategorypath = $qaa['category_path'];
    }

    $sqlgettags = "SELECT * FROM blog_tags WHERE blog_post_id = '$blogpostid'";
    $sm = $dba->prepare($sqlgettags);
    $sm->execute();
    $q = $sm->fetch();

    if($q) {
        $blogtags = $q['tag'];
        $blogtagsarr = explode(",", $blogtags);
    }
}


?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>Maher's Blog | <?php echo $blogmetatitle; ?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/styles.css">

    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
    <script defer src="js/fontawesome/all.min.js"></script>

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">

</head>

<body id="top">


    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader"></div>
    </div>


    <?php include "header-opaque.php"; ?>

    <!-- content
    ================================================== -->
    <section class="s-content">

        <div class="row">
            <div class="column large-12">

                <article class="s-content__entry format-standard">

                    <div class="s-content__media">
                        <div class="s-content__post-thumb">
                            <img src="<?php echo $blogmainimgurl; ?>" srcset="<?php echo $blogmainimgurl; ?> 2100w, 
                                        <?php echo $blogmainimgurl; ?> 1050w, 
                                        <?php echo $blogmainimgurl; ?> 525w" sizes="(max-width: 2100px) 100vw, 2100px"
                                alt="">
                        </div>
                    </div> <!-- end s-content__media -->

                    <div class="s-content__entry-header">
                        <h1 class="s-content__title s-content__title--post"><?php echo $blogtitle; ?></h1>
                    </div> <!-- end s-content__entry-header -->

                    <div class="s-content__primary">

                        <div class="s-content__entry-content">

                            <?php echo $blogcontent; ?>

                        </div> <!-- end s-entry__entry-content -->

                        <div class="s-content__entry-meta">

                            <div class="entry-author meta-blk">
                                <div class="author-avatar">
                                    <img class="avatar" src="images/avatars/maher.png" alt="">
                                </div>
                                <div class="byline">
                                    <span class="bytext">Posted By</span>
                                    <a href="#">Maher</a>
                                </div>
                            </div>

                            <div class="meta-bottom">

                                <div class="entry-cat-links meta-blk">
                                    <div class="cat-links">
                                        <span>In</span>
                                        <a
                                            href="categories.php?group=<?php echo $blogcategorypath;?>"><?php echo $categorytitle; ?></a>
                                    </div>

                                    <span>On</span>
                                    <?php echo date("M j, Y", strtotime($blogcreationdate)); ?>
                                </div>

                                <div class="entry-tags meta-blk">
                                    <span class="tagtext">Tags</span>
                                    <?php 
                                    
                                    for ($i = 0; $i < count($blogtagsarr); $i++) {
                                        if (!empty($blogtagsarr[$i])) {
                                            echo "<a href='search.php?query=".$blogtagsarr[$i]."'>".$blogtagsarr[$i]."</a>";
                                        }
                                    }
                                    
                                    ?>
                                </div>

                            </div>

                        </div> <!-- s-content__entry-meta -->

                        <div class="s-content__pagenav">

                            <?php

                            $sqlgetprevious = "SELECT * FROM blog_post WHERE  blog_post_id = 
                                    (SELECT max(blog_post_id) FROM blog_post WHERE blog_post_id < '" . $blogpostid . "') AND post_status = '1'";
                            $smt = $dba->prepare($sqlgetprevious);
                            $smt->execute();
                            $qr = $smt->fetch(); 
                            
                            $sqlgetnext = "SELECT * FROM blog_post WHERE  blog_post_id = 
                                    (SELECT min(blog_post_id) FROM blog_post WHERE blog_post_id > '" . $blogpostid . "') AND post_status = '1'";
                             $smta = $dba->prepare($sqlgetnext);
                             $smta->execute();        
                             $qra = $smta->fetch();

                             if($qr) {
                                $previousblogname = $qr['post_title'];
                                $previousblogpath = $qr['post_path'];

                                echo "<div class='prev-nav'>
                                <a href='single-blog.php?blog=".$previousblogpath."' rel='prev'>
                                    <span>Previous</span>
                                    ".$previousblogname."
                                </a>
                            </div>";
                             }

                             if($qra) {
                                $nextblogname = $qra['post_title'];
                                $nextblogpath = $qra['post_path'];

                                echo "<div class='next-nav'>
                                <a href='single-blog.php?blog=".$nextblogpath."' rel='next'>
                                    <span>Next</span>
                                    ".$nextblogname."
                                </a>
                            </div>";
                             }

                            ?>

                        </div> <!-- end s-content__pagenav -->

                    </div> <!-- end s-content__primary -->
                </article> <!-- end entry -->

            </div> <!-- end column -->
        </div> <!-- end row -->

        <?php
         $sqlgetallcomments = "SELECT * FROM blog_comments WHERE blog_post_id = :blogpostid";
         $smt = $dba->prepare($sqlgetallcomments);
         $smt->bindParam(':blogpostid', $blogpostid);
         $smt->execute();
         $rowcolm = $smt->rowCount();
         ?>
        <!-- comments
        ================================================== -->
        <div class="comments-wrap">

            <div id="comments" class="row">
                <div class="column large-12">

                    <h3><?php echo $rowcolm; ?> Comments</h3>

                    <!-- START commentlist -->
                    <ol class="commentlist" id="commentlist">

                        <?php

                        $sqlgetcomments = "SELECT * FROM blog_comments WHERE blog_post_id = :blogpostid 
                        AND  blog_comment_parent_id = '0' ORDER BY date_created ASC";        
                        $smtz = $dba->prepare($sqlgetcomments);
                        $smtz->bindParam(':blogpostid', $blogpostid);
                        $smtz->execute();
                        $colsa = $smtz->FETCHALL(PDO::FETCH_OBJ);

                        foreach($colsa as $comments) {
                            $commentid = $comments-> blog_comment_id;
                            $commentauthor = $comments-> comment_author;
                            $comment = $comments-> comment;
                            $commentdate = $comments-> date_created;

                        $sqlcheckcommentchildren = "SELECT * FROM blog_comments WHERE blog_comment_parent_id = 
                         :commentid ORDER BY date_created ASC ";
                           $smtd = $dba->prepare($sqlcheckcommentchildren);
                           $smtd->bindParam(':commentid', $commentid);
                           $smtd->execute();
                           $numcom = $smtd->rowCount();
                            $colsw = $smtd->FETCHALL(PDO::FETCH_OBJ);

                        if ($numcom == 0) {
                            ?>

                        <li class="depth-1 comment">

                            <div class="comment__content">

                                <div class="comment__info">
                                    <input type="hidden" id="comment-author-<?php echo $commentid; ?>"
                                        value="<?php echo $commentauthor; ?>" />
                                    <div class="comment__author"><?php echo $commentauthor; ?></div>

                                    <div class="comment__meta">
                                        <div class="comment__time">
                                            <?php echo date("M j, Y", strtotime($commentdate)); ?>
                                        </div>
                                        <div class="comment__reply">
                                            <a class="comment-reply-link" href="#reply-comment-section"
                                                onclick="prepareReply('<?php echo $commentid; ?>')">Reply</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="comment__text">
                                    <p><?php echo $comment; ?></p>
                                </div>

                            </div>

                        </li>

                        <?php
                        }    
                        else {
                            ?>
                        <li class="thread-alt depth-1 comment">

                            <div class="comment__content">

                                <div class="comment__info">
                                    <input type="hidden" id="comment-author-<?php echo $commentid; ?>"
                                        value="<?php echo $commentauthor; ?>" />
                                    <div class="comment__author"><?php echo $commentauthor; ?></div>

                                    <div class="comment__meta">
                                        <div class="comment__time">
                                            <?php echo date("M j, Y", strtotime($commentdate)); ?>
                                        </div>
                                        <div class="comment__reply">
                                            <a class="comment-reply-link" href="#reply-comment-section"
                                                onclick="prepareReply('<?php echo $commentid; ?>')">Reply</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="comment__text">
                                    <p><?php echo $comment; ?></p>
                                </div>

                            </div>

                            <?php
                        foreach($colsw as $colw) {
                            $commentidchild = $colw-> blog_comment_id;
                            $commentauthorchild = $colw-> comment_author;
                            $commentchild = $colw-> comment;
                            $commentdatechild = $colw-> date_created;

                            echo "<ul class='children'>

                            <li class='depth-2 comment'>

                                <div class='comment__content'>

                                    <div class='comment__info'>
                                        <div class='comment__author'>".$commentauthorchild."</div>

                                        <div class='comment__meta'>
                                            <div class='comment__time'><?php echo date('M j, Y', strtotime($commentdatechild)) ?>
                </div>
            </div>
        </div>

        <div class='comment__text'>
            <p>".$commentchild."</p>
        </div>

        </div>
        </li>
        </ul>";
        }
        }
        }
        ?>
        </li>


        </ol>
        <!-- END commentlist -->

        </div> <!-- end col-full -->
        </div> <!-- end comments -->


        <div class="row comment-respond" id="reply-comment-section">

            <!-- START respond -->
            <div id="respond" class="column">

                <h3 id="reply-h3">

                </h3>

                <p style="color:green; display:none;" id="reply-success">Your reply was added successfully.Refresh
                    your page to view it.</p>
                <p style="color:red; display:none;" id="reply-error"></p>

                <form name="replyForm" id="replyForm">
                    <fieldset>
                        <input type="hidden" name="replyblogpostid" id="replyblogpostid"
                            value="<?php echo $blogpostid; ?>" />
                        <input type="hidden" name="commentparentid" id="commentparentid" value="" />
                        <div class="form-field">
                            <input name="replycName" id="replycName" class="h-full-width h-remove-bottom"
                                placeholder="Your Name" value="" type="text">
                        </div>

                        <div class="form-field">
                            <input name="replycEmail" id="replycEmail" class="h-full-width h-remove-bottom"
                                placeholder="Your Email" value="" type="text">
                        </div>

                        <div class="message form-field">
                            <textarea name="replycMessage" id="replycMessage" class="h-full-width"
                                placeholder="Your Message"></textarea>
                        </div>

                        <br>
                        <input name="submit" id="submitreplyform"
                            class="btn btn--primary btn-wide btn--large h-full-width" value="Reply" type="submit">
                        <input name="submit" id="addcomment" class="btn btn--primary btn-wide btn--large h-full-width"
                            value="Add comment" onclick="prepareComment();">

                    </fieldset>
                </form> <!-- end form -->

            </div>
            <!-- END respond-->

        </div> <!-- end comment-respond -->

        <div class="row comment-respond" id="add-comment-section">

            <!-- START respond -->
            <div id="respond" class="column">

                <h3>
                    Add Comment
                    <span>Your email address will not be published.</span>
                </h3>

                <p style="color:green; display:none;" id="comment-success">You comment was added successfully.</p>
                <p style="color:red; display:none;" id="comment-error"></p>

                <form name="commentForm" id="commentForm">
                    <fieldset>
                        <input type="hidden" name="blogpostid" id="blogpostid" value="<?php echo $blogpostid; ?>" />
                        <div class="form-field">
                            <input name="cName" id="cName" class="h-full-width h-remove-bottom" placeholder="Your Name"
                                value="" type="text">
                        </div>

                        <div class="form-field">
                            <input name="cEmail" id="cEmail" class="h-full-width h-remove-bottom"
                                placeholder="Your Email" value="" type="text">
                        </div>

                        <div class="message form-field">
                            <textarea name="cMessage" id="cMessage" class="h-full-width"
                                placeholder="Your Message"></textarea>
                        </div>

                        <br>
                        <input name="submit" id="submitcommentform"
                            class="btn btn--primary btn-wide btn--large h-full-width" value="Add Comment" type="submit">

                    </fieldset>
                </form> <!-- end form -->

            </div>
            <!-- END respond-->

        </div> <!-- end comment-respond -->

        </div> <!-- end comments-wrap -->


    </section> <!-- end s-content -->


    <?= include "footer.php"; ?>


    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="js/jquery-3.6.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
    <script>
    $(document).ready(function() {
        prepareComment();
    });

    function checkEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        } else {
            return true;
        }
    }

    function prepareReply(commentid) {
        $("#comment-success").css("display", "none");
        $("#comment-error").css("display", "none");
        $("#reply-comment-section").show();
        $("#add-comment-section").hide();
        // this is to get the author of the comment name
        var authorname = $("#comment-author-" + commentid).val();
        $("#reply-h3").html("Reply to: " + authorname);
        $("#commentparentid").val(commentid);
    }

    function prepareComment() {
        $("#comment-success").css("display", "none");
        $("#comment-error").css("display", "none");
        $("#reply-comment-section").hide();
        $("#add-comment-section").show();
    }

    $(document).on('submit', '#commentForm', function(e) {
        // disable the form once it's already submitted
        e.preventDefault();

        $("#comment-success").css("display", "none");
        $("#comment-error").css("display", "none");

        var name = $("#cName").val();
        var email = $("#cEmail").val();
        var comment = $("#cMessage").val();

        if (!name || !email || !comment) {
            $("#comment-error").css("display", "block");
            $("#comment-error").html("Please fill all fields.");
        } else if (name.length > 50) {
            $("#comment-error").css("display", "block");
            $("#comment-error").html("The name field can only be a max of 50 characters.");
        } else if (email.length > 50) {
            $("#comment-error").css("display", "block");
            $("#comment-error").html("The email field can only be a max of 50 characters.");
        } else if (comment.length > 500) {
            $("#comment-error").css("display", "block");
            $("#comment-error").html("The comment field can only be a max of 500 characters.");
        } else if (checkEmail(email) == false) {
            $("#comment-error").css("display", "block");
            $("#comment-error").html("Please enter a valid email address.");
        } else {
            var date = new Date();
            var months = ["JAN", "FEB", "MAR", "APR", "MAI", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV",
                "DEC"
            ];
            var dateFormatted = months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();

            $.ajax({
                method: "POST",
                url: "includes/add-comment.php",
                data: $(this).serialize(),
                success: function(data) {
                    if (data == "success") {
                        var newComment =
                            "<li class='depth-1 comment'><div class='comment__content'><div class='comment__info'><div class='comment__author'>" +
                            name + "</div><div class='comment__meta'><div class='comment__time'>" +
                            dateFormatted + "</div></div></div><div class='comment__text'><p>" +
                            comment + "</p></div></div></li>";
                        $("#comment-success").css("display", "block");
                        $("#commentlist").append(newComment);
                        $("#commentForm").hide();
                    } else {
                        $("#comment-error").css("display", "block");
                        $("#comment-error").html(
                            "There was an error while adding your comment, please try again later."
                        );
                    }
                }
            });
        }

    });

    $(document).on('submit', '#replyForm', function(e) {
        // disable the form once it's already submitted
        e.preventDefault();

        $("#reply-success").css("display", "none");
        $("#reply-error").css("display", "none");

        var name = $("#replycName").val();
        var email = $("#replycEmail").val();
        var reply = $("#replycMessage").val();
        var parentid = $("#commentparentid").val();

        if (!name || !email || !reply) {
            $("#reply-error").css("display", "block");
            $("#reply-error").html("Please fill all fields.");
        } else if (name.length > 50) {
            $("#reply-error").css("display", "block");
            $("#reply-error").html("The name field can only be a max of 50 characters.");
        } else if (email.length > 50) {
            $("#reply-error").css("display", "block");
            $("#reply-error").html("The email field can only be a max of 50 characters.");
        } else if (reply.length > 500) {
            $("#reply-error").css("display", "block");
            $("#reply-error").html("The message field can only be a max of 500 characters.");
        } else if (checkEmail(email) == false) {
            $("#reply-error").css("display", "block");
            $("#reply-error").html("Please enter a valid email address.");
        } else if (!parentid) {
            $("#reply-error").css("display", "block");
            $("#reply-error").html("There was an unexpected error. Try refreshing the page");
        } else {
            var date = new Date();
            var months = ["JAN", "FEB", "MAR", "APR", "MAI", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV",
                "DEC"
            ];
            var dateFormatted = months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();

            $.ajax({
                method: "POST",
                url: "includes/add-reply.php",
                data: $(this).serialize(),
                success: function(data) {
                    if (data == "success") {
                        $("#reply-success").css("display", "block");
                        $("#replyForm").hide();
                    } else {
                        $("#reply-error").css("display", "block");
                        $("#reply-error").html(
                            "There was an error while adding your reply, please try again later."
                        );
                    }
                }
            });
        }

    });
    </script>
</body>

</html>