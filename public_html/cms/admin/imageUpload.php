<?php
/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 17-05-2017
 */ ?>
<?php
session_start();
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
}
$mode = setMode();
$strModuleName = "Brugere";
if ($auth->user->sysadmin || $auth->user->admin) {
    switch (strtoupper($mode)) {

        Default:
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");
        $image = new image();
            $image->GetList();

            $iImageID = filter_input(INPUT_GET, "iImageID", FILTER_SANITIZE_NUMBER_INT);
            ?>
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <h4>Upload Table</h4>
                        <a class="btn btn-success" href="imageUpload.php?mode=Edit&iImageID=-1">Upload nyt billede!</a>
                        <div class="table-responsive">

                            <table class="table table-bordred table-striped">
                                <?php
                                $list = new listpresenter();
                                $list->ListMaker($image->arrLabels, $image->unset, $image->arrValues, "iImageID", false);
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <?php


            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;

        case "DETAILS":
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");            $image = new image();

            $iImageID = filter_input(INPUT_GET, "iImageID", FILTER_SANITIZE_NUMBER_INT);

            $image->getDetails($iImageID);
            ?>
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <h4>Bruger Details</h4>
                        <div class="table-responsive">


                            <table class="table table-bordred table-striped">
                                <?php
                                echo "<th>Detail</th>
                                <th>Value</th>
                                </thead>";
                                foreach ($image->arrValues as $key => $array_values) {
                                    $array_values["daCreated"] = date("d - F, Y", $array_values["daCreated"]);
                                    foreach ($image->arrLabels as $key => $value) {
                                        if (!in_array($value["Field"], $image->unset)) {

                                            echo "<tr>\n";
                                            echo "<td style='font-weight:600;'>" . $value["Comment"] . "</td>\n";
                                            echo "<td style='font-weight:300 ;'>" . $array_values[$value["Field"]] . "</td>\n";
                                            echo "</tr>\n";

                                        }
                                    }
                                } ?>

                                <?php
                                echo "<td><div class='col-lg-6'>
                                    <h3>Image Preview</h3>
                                </div></td>";
                                echo "<td><img src='" . $array_values["vcImagePath"] . "'></td>";
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php


            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;

        case "EDIT":
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");            $image = new image();
            $fields = $image->arrFormElm;
            $iImageID = filter_input(INPUT_GET, "iImageID", FILTER_SANITIZE_NUMBER_INT);
            $select = $image->GetSelect();

            ?>

            <div class="col-lg-4 col-lg-offset-4">
                <form action='../assets/scripts/imageUpload.php' method="post" enctype='multipart/form-data'>
                    <label>Billede</label>
                    <input class="form-control" name='vcImage' type='file'>


                    <?php
                    $FormTypes = array(
                        "vcTitle" => array("label" => "Billede Titel", "type" => "text", "placeholder" => "Billede Titel", "required" => "data-requried='1'", "dbname" => "vcTitle", "Filter" => FILTER_SANITIZE_NUMBER_INT),
                    );

                    foreach ($FormTypes as $key => $value) {


                        echo " <div class='form-group' > ";
                        //Makes a label from arrFormElm array
                        echo "<label> " . $value["label"] . " </label ></br > ";
                        //Makes a input from arrFormElm array & from Class data
                        echo "<input class='form-control' name = '" . $value["dbname"] . "' " . $value["required"] . " type = '" . $value["type"] . "' placeholder = '" . $value["placeholder"] . "')><br > \n";
                        echo " </div> \n";

                    }

                    ?>
                    <input type="submit" onclick="validate(this.form)" name="submit">


                </form>
            </div>

            <?php
            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;

        case "DELETE":
            $obj = new image();
            $id = filter_input(INPUT_GET, "iImageID", FILTER_VALIDATE_INT);
            //Runs $obj->Deleteimage on load
            $obj->Deleteimage($id);
            //redicrects to Mode list after running Deleteimage
            header("location: ?mode=list");

            break;
        case "SAVE":
            $id = filter_input(INPUT_POST, "iImageID", FILTER_SANITIZE_NUMBER_INT);


            if ($id > 0) {
                showme($_POST);

                $obj = new image();
                //Runs $obj->Deleteimage on load
                $obj->Updateimage($id);
                //redicrects to Mode list after running Deleteimage
                header("location: public_html/cms/admin/imageUpload.php?mode=list");
            }
            if ($id == -1) {

                showme($_POST);
                $obj = new image();
                //Runs $obj->Deleteimage on load
                $obj->Createimage($id);
                //redicrects to Mode list after running Deleteimage
                header("location: public_html/cms/admin/imageUpload.php?mode=list");
            }
            break;
    }
}
