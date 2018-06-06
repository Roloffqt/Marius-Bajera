<?php
/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 19-06-2017
 **/

 session_start();
 if (is_file($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php")) {
     require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
 } else {
     require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
 }

//default $file = $_files['"file"'];

/**
 * Change $_Files['Name of input field'] to whatever is fitting
 */
if (isset($_POST["submit"])) {
    $file = $_FILES['vcImage'];

    $fileName = $_FILES['vcImage']['name'];
    $filetmpName = $_FILES['vcImage']['tmp_name'];
    $fileSize = $_FILES['vcImage']['size'];
    $fileError = $_FILES['vcImage']['error'];
    $fileType = $_FILES['vcImage']['type'];



    $fileExT = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExT));



    /**
     * Change $allowed(array) add the file types you wanna allow to be uploaded
     */
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if ($_POST["vcTitle"] == NULL) {
        require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");
         echo "<h1 style='text-align:center;'>Billede navn er tomt!<br> <a style='text-align:center;' href='cms/admin/imageUpload.php?mode=list'>Tilbage!</a></h1>";
        require_once (DOCROOT . "/cms/assets/incl/footer.php");
    } else {
        if (in_array($fileActualExt, $allowed)) {



                /**
                 * $fileSize = max size of file thats uploadable
                 * $fileDestination = which folder the files are stored in, mostly a folder called "Uploads"
                 */
                if ($fileSize < 10000000000000) {
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = '/public_html/uploads/' . $fileNameNew;

                    move_uploaded_file($filetmpName, $_SERVER["DOCUMENT_ROOT"] . $fileDestination);


                    /**
                     * Change SQL statement so it fits the use for your current project
                     */
                    $sql = "INSERT INTO image(vcImagePath, vcTitle, daCreated) VALUES(?,?,?)";
                    $db->_query($sql, array(
                        $fileDestination,
                        $_POST["vcTitle"],
                        time()
                    ));


                    header("location: /imageUpload.php?mode=list");

                } else {

                    /**
                     * File size Error
                     */
                    require_once (DOCROOT . "/cms/assets/incl/header.php");
                    require_once (DOCROOT . "/cms/assets/incl/nav.php");                    echo "<h1 style='text-align: center;'>Your File is too big!</h1>";
                    echo "<h1 style='text-align: center;'>File has to be 10 Mb or less!</h1>";
                    require_once (DOCROOT . "/cms/assets/incl/footer.php");
                }

        } else {

            /**
             * File format Error
             */
            require_once (DOCROOT . "/cms/assets/incl/header.php");
            require_once (DOCROOT . "/cms/assets/incl/nav.php");            echo "<h1 style='text-align: center;'>Wrong file format!</h1>";
            echo "<h2 style='text-align: center;'>You can only upload 'jpg', 'jpeg', 'png', 'pdf' </h2>";
            require_once (DOCROOT . "/cms/assets/incl/footer.php");
        }

    }
}
?>
