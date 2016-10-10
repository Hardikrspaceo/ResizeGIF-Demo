<?php
if(isset($_FILES) && !empty($_FILES)){
    $newFileName = "";
    $dirname     = "uploads/";
    $uploadPath  = $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'].$dirname;

    if(!file_exists($uploadPath)){
        mkdir($uploadPath);
        chmod($uploadPath, 0777);
    }
    $types = array('jpeg', 'gif','png');
    $filename    = $_FILES["image"]["name"];
    $extension   = end(explode(".", $filename));
    if(in_array($extension,$types)){
        $newfilename = "image.".$extension;

        //read images and delete last
        $images = glob($dirname."*");
        if(!empty($images)){
            foreach($images as $image) {
                if(is_file($image))
                    @unlink($image);
            }
        }
        //upload new image
        if(move_uploaded_file($_FILES['image']['tmp_name'],$uploadPath.$newfilename)){
            chmod($uploadPath.$newfilename, 0777);
        }else{
            echo "Something went wrong please try again. <br/><br/>";
        }
    }else{
        echo "Sorry your file was not uploaded. It may be the wrong filetype. We only allow JPG, GIF, and PNG filetypes. <br/><br/>";
    }
}
?>

<html>
<head>

</head>
<body>
    <fieldset style="width: 50%">
        <legend>Image Upload</legend>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="file" name="image" value="select..">
            <input type="submit" value="Upload" name="upload">
        </form>
    </fieldset>
    <br/><br/>
    <?php
        $dirname = "uploads/";
        $images = glob($dirname."*");
        if(!empty($images)){
            foreach($images as $image) {
                echo '<img src="'.$image.'" height="300" width="300" /><br />';
            }
        }
    ?>
</body>
</html>
