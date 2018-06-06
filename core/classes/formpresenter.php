<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 19-05-2017
 */
class formpresenter
{
    public $arrLabels;
    public $arrValues;
    public $arrColumns;
    public $unset = array();
    private $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }
    /**
     * Form types = Array in "class".php class
     * Class = data from DB
     * $select = array from DB, with data
     *
     * Call this function in backend and make arrFormElm array and specifice what you want in the array
     **/
    public function MakeForm($FormTypes, $class = null, $select = null, $selectcat = null, $classData, $file = false, $selectbox = false, $selectImage = false, $selectcate = false)
    {
        echo "<form method='POST' data-validate ";
        if ($file == true) {
            echo "action='/cms/assets/scripts/upload.php'";
        }

        echo "enctype='multipart/form-data'>";
//Foreaches Formtypes to make input, formtypes is an array in User.php class that holds the values for each input field
        echo "<input name='mode' type='hidden' value='SAVE'>";

        if ($file == true) {
            echo "<input name='file' type='file'>";
        }

        foreach ($FormTypes as $key => $value) {


            if ($value["type"] === "text" || $value["type"] === "date" || $value["type"] === "password" || $value["type"] === "email" || $value["type"] === "tel" || $value["type"] === "hidden") {

                echo "<div class='form-group'>";
                //Makes a label from arrFormElm array
                echo "<label>" . $value["label"] . "</label></br>";

                //Makes a input from arrFormElm array & from Class data
                echo "<input data-requried=\"1\" class='form-control'  name='" . $value["dbname"] . "' type='" . $value["type"] . "' placeholder='" . $value["placeholder"] . "' value='" . $value["value"] . "'><br>\n";
                echo "</div>\n";
            }


            if ($value["type"] === "textarea") {
                echo "<div class='form-group'>";
                //Makes a label from arrFormElm array
                echo "<label>" . $value['label'] . "</label></br>";

                //Makes a input from arrFormElm array & from Class data
                echo "<textarea id='' data-requried=\"1\" class='form-control summernote'  name='" . $value["dbname"] . "' type='" . $value["type"] . "' placeholder='" . $value["placeholder"] . "' value='" . $value["value"] . "'>" .
                    $value["value"] .
                    "</textarea><br>\n";
                echo "</div>\n";

            }
        }


        if ($selectbox == true) {
            //calls $classdata(current class your using) class, to use arrFormElm array
            $classData = new $classData();

            //foreaches $arrFormelm's key to Elmvalue
            foreach ($classData->arrFormElm as $key => $Elmvalue) {

                //Checks for type => select in $user->arrFormElm
                if ($Elmvalue["type"] === "select") {

                    echo "<select class='form-control'>\n";
                    //Foreach $select(Database)
                    foreach ($select as $key => $value) {

                        //Gets $select's value from $elmValue's dbname(array)
                        echo "<option value = '" . $value[$Elmvalue["vcTitle"]] . "'>" . $value[$Elmvalue["dbname"]] . "</option>\n";
                    }
                    echo "</select>\n ";
                }
            }
        }

        if ($selectImage == true) {
            $image = new image();
            foreach ($image->arrFormElm as $key => $Elmvalue) {

                //Checks for type => select in $user->arrFormElm
                if ($Elmvalue["type"] === "select") {
                    echo "<label>Billede</label>";
                    echo "<select name='vcImage' class='form-control'>\n";
                    //Foreach $select(Database)
                    foreach ($select as $value) {

                        //Gets $select's value from $elmValue's dbname(array)
                        echo "<option value = '" . $value[$Elmvalue["dbname"]] . "'>" . $value["vcTitle"] . "</option>\n";
                    }
                    echo "</select>\n ";
                }
            }
        }

        if ($selectcate == true) {
            $category = new shopcategory();
            $rows = $category->getSelect();
            foreach ($category->arrFormElm as $key => $Elmvalue) {

                //Checks for type => select in $user->arrFormElm
                if ($Elmvalue["type"] === "select") {
                    echo "<label>Kategori</label>";
                    echo "<select name='iParentID' class='form-control'>\n";
                    //Foreach $select(Database)

                    echo "<option value='-1'>Over Kategori</option>\n";
                    foreach ($rows as $value) {
                        //Gets $select's value from $elmValue's dbname(array)
                        echo "<option value = '" . $value["iCategoryID"] . "'>" . $value["vcTitle"] . "</option>\n";
                    }
                    echo "</select>\n ";
                }
            }
        }


        echo "<button type='submit' name='submit' class=\"btn btn-success\">GEM</button>";
        echo "<a href='?mode=list' class=\"btn btn-danger\">Tilbage</a>";
        echo "</form>";

    }
}
