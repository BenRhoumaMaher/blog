<?php
    require "dbh.php";
    
    header("Content-Type: application/vnd.ms-excel");
    $filename="blog";
    header("Content-Disposition:attachment;filename= $filename.xls");
?>
<!DOCTYPE html>
<html lang="en">

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
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Views</th>
                            <th>Blog Path</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    
                        
                        $sql = "SELECT * FROM blog_post";
                        
                        $smt = $dba->prepare($sql);
                        $smt->execute();
                        $roww = $smt->fetchAll(PDO::FETCH_OBJ);
                    
                                                $counter = 0;
                                                 foreach($roww as $numblog) 
                                                 {
                                                    $counter++;
                                                    $id = $numblog->blog_post_id;
                                                    $name = $numblog->post_title;
                                                    $views = $numblog->blog_post_views;
                                                    $blogPath = $numblog->post_path;
                                                    ?>
                        <tr>
                            <td><?= $counter; ?></td>
                            <td><?= $name; ?></td>
                            <td><?= $views; ?></td>
                            <td><?= $blogPath; ?></td>
                        </tr>
                        <?php
                                                 }
                                                 ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>