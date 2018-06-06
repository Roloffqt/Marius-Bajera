<?php
session_start();
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
}
$mode = setMode();
$strModuleName = "Work";

if ($auth->user->sysadmin || $auth->user->admin) {
    switch (strtoupper($mode)) {

        case "LIST";
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));

            $strModuleMode = "List of works";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");
            $work = new works();
            $work->GetList();

            $iWorkID = filter_input(INPUT_GET, "iWorkID", FILTER_SANITIZE_NUMBER_INT);

            ?>
            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);

                    ?>

                    <div class="col-md-12">
                        <a class="btn btn-success" href="works.php?mode=Edit&iWorkID=-1">Add new Work</a>
                        <div class="table-responsive">

                            <table class="table table-bordred table-striped">
                                <?php
                                $p = new listPresenter();
                                $p->ListMaker($work->arrLabels, $work->unset, $work->arrValues, "iWorkID");
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

            $iWorkID = filter_input(INPUT_GET, "iWorkID", FILTER_SANITIZE_NUMBER_INT);
            $strModuleMode = "Details";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");            $work = new works();
            $work->getDetails($iWorkID);
            ?>

            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    $arrButtonPanel[] = getButtonLink("table", "?mode=list", "List", "btn-primary");
                    $arrButtonPanel[] = getButtonLink("pencil", "?mode=edit&iWorkID=" . $iWorkID, "Edit Work", "btn-success");
                    $arrButtonPanel[] = getButtonLink("plus", "?mode=SETCATEGORY&iWorkID=" . $iWorkID, "Choose Category", "btn");
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);
                    ?>
                    <div class="col-md-12">
                        <h4>Produkt Details</h4>
                        <div class="table-responsive">


                            <table class="table table-bordred table-striped">
                                <?php
                                $list = new ListPresenter();
                                $list->MakeDetails($work->arrLabels, $work->unset, $work->arrValues);
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

        case "EDIT";


            $iWorkID = filter_input(INPUT_GET, "iWorkID", FILTER_SANITIZE_NUMBER_INT);
            $work = new works($iWorkID);
                $fields = $work->arrFormElm;
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));


            //Makes $select into image parameter!
            $image = new image();
            $selectimg = $image->GetSelect();

            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");            $strModuleMode = ($iWorkID > 0) ? "Edit" : "Create New Work";


            ?>
            <div class="container">
                <div class="row">
                    <?php

                    $arrButtonPanel = array();
                    $arrButtonPanel[] = getButtonLink("table", "?mode=list", "Oversigt", "btn-primary");
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);

                    ?>
                    <div class="col-md-12">
                        <?php
                        $form = new formpresenter();
                        $form->MakeForm($fields, $work, $selectimg, false, "works", false, true, true, false);
                        ?>
                    </div>
                </div>
            </div>

            <?php
            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;

case
        "SETCATEGORY":
            $iWorkID = isset($_GET["iWorkID"]) && is_int(intval($_GET["iWorkID"])) ? $_GET["iWorkID"] : false;


            $strModuleMode = "Category";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");

            $arrSelected = array();
            $params = array($iWorkID);

            $strSelect = "SELECT iCategoryID FROM shopcatprodrel WHERE iWorkID = ?";
            $rel = $db->_fetch_array($strSelect, $params);

            foreach ($rel as $value) {
                $arrSelected[] = $value["iCategoryID"];
            }


            $cate = new shopcategory();
            $rows = $cate->getSelect();


            $arrFormValues = array();
            $arrFormValues["iWorkID"] = $iWorkID;


            ?>

            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <form method="POST">
                            <input type='hidden' name='mode' value='SAVECATEGORY'>
                            <?php
                            $arrButtonPanel = array();
                            $arrButtonPanel[] = getButtonLink("plus", "?mode=list", "Oversigt", "btn");
                            echo textPresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);

                            foreach ($rows as $key => $arrValues) {
                                $field = $arrValues["iCategoryID"];
                                $arrFormValues[$field] = in_array($arrValues["iCategoryID"], $arrSelected) ? 1 : 0;
                                $strIsChecked = $arrFormValues[$field] ? "checked=\"checked\"" : "";
                                ?>
                                <div class="col-md-12 form-group">
                                    <label>
                                        <?php echo $arrValues["vcTitle"] ?>
                                        <input type="checkbox" name="groups[]" value="<?php echo $field; ?>" <?php echo $strIsChecked ?>>
                                    </label>
                                </div>
                                <?php
                            } ?>
                            <button type="submit" name='submit' onclick="validate(this.form)" class="btn btn-success">GEM</button>
                            <a href='?mode=list' class="btn btn-danger">Tilbage</a>
                        </form>
                    </div>
                </div>
            </div>

            <?php


            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;


        case "SAVECATEGORY":
            showme($_POST);

            $iWorkID = Filter_input(INPUT_GET, "iWorkID", FILTER_SANITIZE_NUMBER_INT);
            $params = array($iWorkID);
            $strDelete = "DELETE FROM shopcatprodrel WHERE iWorkID = ?";
            $db->_query($strDelete, $params);

            $args = array(
                "groups" => array(
                    "filter" => FILTER_VALIDATE_INT,
                    "flags" => FILTER_REQUIRE_ARRAY
                )
            );
            $arrInputVal = filter_input_array(INPUT_POST, $args);

            if (count($arrInputVal["groups"])) {

                $arrGroups = array_values($arrInputVal["groups"]);

                foreach ($arrGroups as $value) {
                    $params = array($iWorkID, $value);
                    $strInsert = "INSERT INTO shopcatprodrel(iWorkID, iCategoryID) VALUES(?,?)";
                    $db->_query($strInsert, $params);
                }
            }
            header("location: ?mode=details&iWorkID=" . $iWorkID);

            break;


        case "DELETE":
            $obj = new works();
            $id = filter_input(INPUT_GET, "iWorkID", FILTER_VALIDATE_INT);
            $obj->Deleteworks($id);
            header("Location: ?mode=list");
            break;


        case "SAVE":
            $id = filter_input(INPUT_POST, "iWorkID", FILTER_SANITIZE_NUMBER_INT);

            $obj = new works();

            if ($id > 0) {
                //Runs $obj->DeleteEvent on load
                $obj->Updateworks($id);
                //redicrects to Mode list after running DeleteEvent
            } else {
                //Runs $obj->DeleteEvent on load
                $obj->Createworks($id);
                //redicrects to Mode list after running DeleteEvent
            }

            header("Location: ?mode=list");

    }
}
