<?php

require_once "../admin/includes/dbh.php";

$blogpostid = isset($_POST['blogpostid']) ? $_POST['blogpostid']  : "";
$commentsendername = isset($_POST['cName']) ? $_POST['cName']  : "";
$commentemail = isset($_POST['cEmail']) ? $_POST['cEmail']  : "";
$comment = isset($_POST['cMessage']) ? $_POST['cMessage']  : "";

$date = date("Y-m-d");
$time = date("H:i:s");

$sqladdcomment = "INSERT INTO blog_comments(blog_post_id,comment_author,comment_author_email,comment,date_created,time_created)
VALUES(:blogpostid,:commentsendername,:commentemail,:comment,:date,:time)";
$smt = $dba->prepare($sqladdcomment);
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