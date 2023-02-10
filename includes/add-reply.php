<?php

require_once "../admin/includes/dbh.php";

$blogpostid = isset($_POST['replyblogpostid']) ? $_POST['replyblogpostid']  : "";
$commentparentid = isset($_POST['commentparentid']) ? $_POST['commentparentid']  : "";
$commentsendername = isset($_POST['replycName']) ? $_POST['replycName']  : "";
$commentemail = isset($_POST['replycEmail']) ? $_POST['replycEmail']  : "";
$comment = isset($_POST['replycMessage']) ? $_POST['replycMessage']  : "";

$date = date("Y-m-d");
$time = date("H:i:s");

$sqladdreply = "INSERT INTO blog_comments(blog_comment_parent_id,blog_post_id,comment_author,comment_author_email,comment,date_created,time_created)
VALUES(:commentparentid,:blogpostid,:commentsendername,:commentemail,:comment,:date,:time)";
$smt = $dba->prepare($sqladdreply);
$smt->bindParam(':commentparentid', $commentparentid);
$smt->bindParam(':blogpostid', $blogpostid);
$smt->bindParam(':commentsendername', $commentsendername);
$smt->bindParam(':commentemail', $commentemail);
$smt->bindParam(':comment', $comment);
$smt->bindParam(':date', $date);
$smt->bindParam(':time', $time);

$smt->execute();

if(!$smt) {
    $result = "error";
}
else {
    $result = "success";
}
echo $result;