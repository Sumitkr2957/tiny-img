<?php
    // echo ini_get('post_max_size');
    // echo ini_get('upload_max_filesize');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiny Image</title>
    <style type="text/css">
        body{
            color: rgb(16,16,16);
            background-color: rgb(192, 192, 192);
            font-size: 20px;
            text-align: center;
            font-family: 'sans-serif', helvetica, verdana;
        }
        form{
            margin-top: 10%;
            padding: 30px;
            display: inline-block;
            text-align: left;
            background-color: rgba(240, 240, 240, 0.5);
            border: 5px dotted white;            
            font-weight: bold;
        }
        input[type=submit]{
            margin-left: 50%;
            transform: translate(-50%);
        }
        #info{
            display: inline-block;
            text-align: left;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label for="img-field">Choose an image : </label>
        <input type="file" accept="image/jpeg" name="img-field" id="img-field" required><br><br>
        <label for="quality">Output quality 10-90 : </label>
        <input type="number" name="quality" id="quality" min="10" max="90" required><br><br>
        <input type="submit" name="submit" value="Compress"><br>
    </form><br><br>

    <?php

        if(isset($_POST['submit'])){


            function compressImage($source, $destination, $quality) {
                $image = imagecreatefromjpeg($source);
                imagejpeg($image, $destination, $quality);
                $old_size = round(filesize($source)/1024, 2);
                $new_size = round(filesize($destination)/1024, 2);
                $compression = 100 - round(($new_size/$old_size)*100, 2);
                echo "<b>New Size : </b>$new_size KB<br>";
                echo "<b>Compression : </b>$compression %<br>";
                echo "<b>Compressed : </b><a href='$destination' download>$destination</a>";
            }

            extract($_POST, EXTR_OVERWRITE);

            if(isset($_FILES['img-field'])){
                extract($_FILES['img-field'], EXTR_OVERWRITE);
            }

            echo "<div id='info'>";
            echo "<b>File :&#9;</b>$name<br>";
            echo "<b>Size : </b>".round($size/1024, 2)." KB<br>";
            echo "<b>Quality : </b>$quality<br>";

            $valid_ext = array('jpeg','jpg');
            $file_extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            if(in_array($file_extension,$valid_ext)){

                compressImage($tmp_name ,explode('.', $name)[0]."_compressed_".time().".$file_extension", $quality);

            }else{
                echo "<b>Error : </b>Invalid File type<br>";
            }

            echo "</div>";
        }
   ?>
</body>
</html>
