<?php include "includes/admin_header.php"; ?>
        <div id="wrapper">
            <!-- Navigation -->
            <?php include "includes/admin_navigation.php" ?>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">
                                Comment Control Page
                                <small><?php echo $_SESSION['username']; ?></small>
                            </h1>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">Id</th>
                                    <th class="text-center">Author</th>
                                    <th class="text-center">Comment</th>
                                    <th class="text-center">E-mail</th>
                                    <th class="text-center">Post Title</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                   <?php
                                    $query = "select * from comments where comment_post_id =" . mysqli_real_escape_string($connection, $_GET['id']) . " ";
                                    $select_comments = mysqli_query($connection, $query);

                                    confirmQuery($select_comments);
                                    while($row = mysqli_fetch_assoc($select_comments)) {
                                        $comment_id = $row['comment_id'];
                                        $comment_post_id = $row['comment_post_id'];
                                        $comment_author = $row['comment_author'];
                                        $comment_email = $row['comment_email'];
                                        $comment_content = $row['comment_content'];
                                        $comment_status = $row['comment_status'];
                                        $comment_date = $row['comment_date'];

                                        echo "<tr>";
                                        echo "<td>{$comment_id}</td>";
                                        echo "<td>{$comment_author}</td>";
                                        echo "<td>{$comment_content}</td>";                                    
                                        echo "<td>{$comment_email}</td>";
                                        $query = "select * from posts where post_id = $comment_post_id";
                                        $select_post_id_query = mysqli_query($connection, $query);
                                        confirmQuery($select_post_id_query);
                                        while($row = mysqli_fetch_assoc($select_post_id_query)) {
                                            $post_id = $row['post_id'];
                                            $post_title = $row['post_title'];
                                            echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
                                        }
                                        echo "<td>{$comment_date}</td>";
                                        if($comment_status == 'Approved') {
                                            echo "<td><a href='post_comments.php?unapprove={$comment_id}&id=" . $_GET['id'] . "'>Approve</a></td>";
                                        } else {
                                            echo "<td><a href='post_comments.php?approve={$comment_id}&id=" . $_GET['id'] . "'>Unapprove</a></td>";
                                        }                                    
                                        echo "<td><a href='post_comments.php?delete={$comment_id}&id=" . $_GET['id'] . "'>Delete</a></td>"; 
                                        echo "</tr>";
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
<?php include "includes/admin_footer.php"; ?>
                    <?php

                    if(isset($_GET['approve'])) {
                        $the_comment_id = $_GET['approve'];
                        $query = "update comments set comment_status = 'Approved' where comment_id = {$the_comment_id} ";
                        $approve_comment_query = mysqli_query($connection, $query);
                        
                        confirmQuery($approve_comment_query);
                        header("Location: post_comments.php?id=" . $_GET['id'] . " ");
                    }

                    if(isset($_GET['unapprove'])) {
                        $the_comment_id = $_GET['unapprove'];
                        $query = "update comments set comment_status = 'Unapproved' where comment_id = {$the_comment_id} ";
                        $unapprove_comment_query = mysqli_query($connection, $query);
                        
                        confirmQuery($unapprove_comment_query);
                        header("Location: post_comments.php?id=" . $_GET['id'] . " ");
                    }

                    if(isset($_GET['delete'])) {
                        $the_comment_id = $_GET['delete'];
                        $query = "delete from comments where comment_id = {$the_comment_id} ";
                        $delete_query = mysqli_query($connection, $query);
                        
                        confirmQuery($delete_query);
                        
                        $the_post_id = $_GET['id'];
                        $query = "update posts set post_comment_count = post_comment_count - 1 where post_id = {$the_post_id} ";
                        $increase_comment_count = mysqli_query($connection, $query);
                        
                        confirmQuery($increase_comment_count);
                        header("Location: post_comments.php?id=" . $_GET['id'] . " ");
                    }
                    ?>