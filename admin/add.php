<?php
session_start();
require '../config/config.php';
require '../config/common.php';


if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    
header('location: login.php');
  
}
if($_SESSION['role'] != 1){
    header('location: login.php');
}

if($_POST){
    if(empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image']['name'])){
        if(empty($_POST['title'])){
            $titleError = 'Title cannot be null!';
        }
        if(empty($_POST['content'])){
            $contentError = 'Content cannot be null!';
        }
        if(empty($_FILES['image']['image'])){
            $imageError = 'Image cannot be null!';
        }

    }else{
        $title = $_POST['title'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];

    if($_FILES['image']['name']){

        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file, PATHINFO_EXTENSION);

    if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png' && $imageType != 'gif'){

        echo"<script>alert('You must be jpg,jpeg,png and gif');window.location.href('add.php');</script>";

    }else{
        move_uploaded_file($_FILES['image']['tmp_name'],$file);
        $image = $_FILES['image']['name'];
        
        $stmt = $pdo->prepare("INSERT INTO posts(title,content,image,author_id) VALUES(:title,:content,:image,:author_id)");
        $result=$stmt->execute(
            array(
                ':title'=>$title,
                ':content'=>$content,
                ':image'=>$image,
                ':author_id'=>$user_id
                )
            );
        
        if($result){
            echo"<script>alert('Successfully added');window.location.href='index.php';</script>";

        }
        

    }
    }else{
        $stmt = $pdo->prepare("INSERT INTO posts(title,content,author_id) VALUES(:title,:content,:author_id)");
        $result=$stmt->execute(
            array(
                ':title'=>$title,
                ':content'=>$content,
                ':author_id'=>$user_id
                )
            );
        
        if($result){
            echo"<script>alert('Successfully added');window.location.href='index.php';</script>";

        }

    }
}
}


?>

<?php include('header.php')?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              
            <div class="card-body">
                <form action="add.php" method="post" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <div class="form-group">
                        <label for="">Title</label><p style="color:red"><?php echo  empty($titleError) ? '' : '*'.$titleError ?></p>
                        <input type="text" name="title" id="" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="">Content</label><p style="color:red"><?php echo  empty($contentError) ? '' : '*'.$contentError ?></p>
                        <textarea name="content" id="" cols="30" rows="10" class="form-control" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Image</label><p style="color:red"><?php echo  empty($imageError) ? '' : '*'.$imageError ?></p>
                        <input type="file" name="image" id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Create" id="" class="btn btn-success">
                        <a href="index.php" type="button" class="btn btn-warning">Back</a>
                    </div>
                </form>
            </div>
              
            </div>
            <!-- /.card -->

            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php include('footer.html')?>
