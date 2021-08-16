<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
    #ques {
        min-height: 433px;
    }
    </style>

    <title>Welcome to iDiscuss - Coding Forums</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_header.php'; ?>

    <?php 

    $id= $_GET['threadid'];
    $sql= "SELECT * FROM `threads` WHERE thread_id=$id";
    $result= mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thread_user_id = $row['thread_user_id'];

        // Query the users table to find out the name of OP

        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn,$sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $posted_by = $row2['user_email'];

    }

    ?>

    <?php
    
    $showAllert=false;
    $method= $_SERVER['REQUEST_METHOD'];
    if($method=='POST'){
        
        //insert comment into db
        
        $comment = $_POST['comment'];
        $sno = $_POST['sno'];
        $comment = str_replace("<", "&lt;" , $comment);
        $comment = str_replace(">", "&gt;" , $comment);
        

        $sql="INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) 
              VALUES ('$comment', '$id', '$sno', current_timestamp())";
        $result=mysqli_query($conn,$sql);
        $showAllert=true;
        if($showAllert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>SUCCESS!</strong> Your comment has been added.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                  </div>';
        }
    }
    
    ?>




    <!-- Category container starts here -->

    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4"> <?php echo $title ; ?> </h1>
            <p class="lead"> <?php echo $desc ; ?> </p>
            <hr class="my-4">
            <p> ♦ This is a peer to peer forum
                ♦No Spam / Advertising /
                ♦ Self-promote in the forums is not allowed.
                ♦ Do not post copyright-infringing material.
                ♦ Do not post “offensive” posts, links or images.
                ♦ Do not cross post questions.
                ♦ Do not PM users asking for help.
                ♦ Remain respectful of other members at all times.</p>
            <p class="lead">
            <p> Posted by: <b><em><?php echo $posted_by; ?> </em></b></p>
            </p>
        </div>

    </div>

    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){

                echo '<div class="container my-5">
                <h1>Post a Comment!</h1>
                <form action=" '. $_SERVER['REQUEST_URI'] .' " method="post">
        
                    <div class="form-group my-1">
                        <label for="exampleFormControlTextarea1">Type your comment</label>
                        <textarea class="form-control my-1" id="comment" name="comment" rows="3"></textarea>
                        <input type="hidden" name="sno" value="' .$_SESSION["sno"]. '">
                    </div>
                    <button type="submit" class="btn btn-success my-4">Post Comment</button>
                </form>
            </div>';
            }
            else{
                echo ' <div class="text-center container-fluid bg-info text-dark">
                <h1 class="py-1">Post a Comment!</h1>
                <p class="lead py-2">Your\'e not Logged in, Please Log in to be able to post Comments!</p>
                </div>';
            }

    ?>

    <div class="container mb-5" id="ques">
        <h1 class="py-2"> Discussions </h1>

        <?php 

    $id= $_GET['threadid'];
    $sql= "SELECT * FROM `comments` WHERE thread_id=$id";
    $result= mysqli_query($conn,$sql);
    $noResult = true;
    while($row = mysqli_fetch_assoc($result)){
        $noResult = false;
        $id = $row['comment_id'];
        $content = $row['comment_content'];
        $comment_time = $row['comment_time'];
        $thread_user_id = $row['comment_by'];

        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn,$sql2);
        $row2 = mysqli_fetch_assoc($result2);
        

   
        echo '<div class="media my-4">
            <img class="mr-3" src="img/user.png" width="44px" alt="Generic placeholder image">
            <div class="media-body">
            <p class="font-weight-bold my-0"><b>'.$row2['user_email'].' at '.$comment_time.'</b></p>
                ' . $content . '
            </div>
        </div>';
    }

    if($noResult){
        echo '<div class="jumbotron jumbotron-fluid">
        <div class="container">
          <p class="display-4">No Comments found</p>
          <p class="lead"><em>Be the first person to Comment</em></p>
        </div>
      </div>';
    }


    ?>







        <?php include 'partials/_footer.php'; ?>


        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous">
        </script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    -->
</body>

</html>