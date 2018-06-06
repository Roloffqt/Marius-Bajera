<?php
//default $file = $_files['"file"'];
if (isset($_POST["submit"])) {
    $file = $_FILES['file'];
    print_r($file);
    $fileName = $_FILES['file']['name'];
    $filetmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];


    $fileExT = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExT));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = $_SERVER["DOCUMENT_ROOT"] . '/uploads/' . $fileNameNew;

                move_uploaded_file($filetmpName, $fileDestination);
                // header("location: /cms/admin.php?Uploadsucess");

            } else {
                echo "<h1 style='text-align: center;'>Your File is too big!</h1>";
            }
        } else {
            echo "<h1 style='text-align: center;'>There was an Error try agian</h1>";
        }
    } else {
        echo "<h1 style='text-align: center;'>Wrong file format!</h1>";
    }

}

?>