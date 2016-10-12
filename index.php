<?php
if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])){
    $newFileName    = "";
    $image_function = "";
    $percent        = 0.5;
    $dirname        = "uploads/";
    $image_create_function = "";
    $uploadPath  = $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'].$dirname;

    if(!file_exists($uploadPath)){
        mkdir($uploadPath);
        chmod($uploadPath, 0777);
    }
    $types = array('jpeg','jpg', 'gif','png');
    $filename    = $_FILES["image"]["name"];
    $extension   = end(explode(".", $filename));
    if(in_array($extension,$types)){
        $newfilename = "image.".$extension;

        //read images and delete last uploaded
        $images = glob($uploadPath."*");
        if(!empty($images)){
            foreach($images as $image) {
                if(is_file($image))
                    @unlink($image);
            }
        }

        // Get new sizes
        list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
        $newwidth   = $width * $percent;
        $newheight  = $height * $percent;

        // image load with new height & width
        $thumb = imagecreatetruecolor($newwidth, $newheight);

        //generate image with diff extension
        if($extension == 'gif'){
            system("convert ".$_FILES['image']['tmp_name']." -coalesce -repage 0x0 -resize ".$newheight."x".$newwidth." -layers Optimize ".$uploadPath.$newfilename);
        }
        else if($extension == 'jpg' || $extension == 'jpeg')
        {
            $new_image = imagecreatefromjpeg($_FILES['image']['tmp_name']);
            imagecopyresized($thumb, $new_image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagejpeg($thumb,$uploadPath.$newfilename);
        }
        else if($extension == 'png')
        {
            $new_image = imagecreatefrompng($_FILES['image']['tmp_name']);
            imagecopyresized($thumb, $new_image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagepng($thumb,$uploadPath.$newfilename);
        }
        else
        {
            echo "Plese upload valid image";
        }
        chmod($uploadPath.$newfilename, 0777);
    }else{
        echo "Sorry your file was not uploaded. It may be the wrong filetype. We only allow JPG, GIF, and PNG filetypes. <br/><br/>";
    }
}else{
    echo "Please select a file. <br/><br/>";
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
