<?php
session_start();
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
}
$mode = setMode();
$strModuleName = "About";

if ($auth->user->sysadmin || $auth->user->admin) {
    switch (strtoupper($mode)) {

        case "LIST";
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));

            $strModuleMode = "List of About";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
            require_once (DOCROOT . "/cms/assets/incl/nav.php");
            $About = new About();
            $About->GetList();

            $iAboutID = filter_input(INPUT_GET, "iAboutID", FILTER_SANITIZE_NUMBER_INT);

            ?>
            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);

                    ?>

                    <div class="col-md-12">
                        <div class="table-responsive">

                            <table class="table table-bordred table-striped">
                                <?php

                                $p = new listPresenter();
                                $p->ListMaker($About->arrLabels, $About->unset, $About->arrValues, "iAboutID", $edit = true, $delete = False);
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

            $iAboutID = filter_input(INPUT_GET, "iAboutID", FILTER_SANITIZE_NUMBER_INT);
            $strModuleMode = "Details";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
            require_once (DOCROOT . "/cms/assets/incl/nav.php");            $About = new About();
            $About->getDetails($iAboutID);
            ?>

            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    $arrButtonPanel[] = getButtonLink("table", "?mode=list", "List", "btn-primary");
                    $arrButtonPanel[] = getButtonLink("pencil", "?mode=edit&iAboutID=" . $iAboutID, "Edit About", "btn-success");
                    $arrButtonPanel[] = getButtonLink("plus", "?mode=SETCATEGORY&iAboutID=" . $iAboutID, "Choose Category", "btn");
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);
                    ?>
                    <div class="col-md-12">
                        <h4>Produkt Details</h4>
                        <div class="table-responsive">
                            <table class="table table-bordred table-striped">
                                <?php
                                $list = new ListPresenter();
                                $list->MakeDetails($About->arrLabels, $About->unset, $About->arrValues);
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


            $iAboutID = filter_input(INPUT_GET, "iAboutID", FILTER_SANITIZE_NUMBER_INT);
            $About = new About($iAboutID);
                $fields = $About->arrFormElm;
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));


            //Makes $select into image parameter!
            $image = new image();
            $selectimg = $image->GetSelect();

            require_once (DOCROOT . "/cms/assets/incl/header.php");
            require_once (DOCROOT . "/cms/assets/incl/nav.php");            $strModuleMode = ($iAboutID > 0) ? "Edit" : "Create New About";


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
                        $form->MakeForm($fields, $About, $selectimg, false, "About", false, true, true, false);
                        ?>
                    </div>
                </div>
            </div>

            <?php
            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;

case
        "SETCATEGORY":
            $iAboutID = isset($_GET["iAboutID"]) && is_int(intval($_GET["iAboutID"])) ? $_GET["iAboutID"] : false;


            $strModuleMode = "Category";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
            require_once (DOCROOT . "/cms/assets/incl/nav.php");

            $arrSelected = array();
            $params = array($iAboutID);

            $strSelect = "SELECT iCategoryID FROM shopcatprodrel WHERE iAboutID = ?";
            $rel = $db->_fetch_array($strSelect, $params);

            foreach ($rel as $value) {
                $arrSelected[] = $value["iCategoryID"];
            }


            $cate = new shopcategory();
            $rows = $cate->getSelect();


            $arrFormValues = array();
            $arrFormValues["iAboutID"] = $iAboutID;


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

            $iAboutID = Filter_input(INPUT_GET, "iAboutID", FILTER_SANITIZE_NUMBER_INT);
            $params = array($iAboutID);
            $strDelete = "DELETE FROM shopcatprodrel WHERE iAboutID = ?";
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
                    $params = array($iAboutID, $value);
                    $strInsert = "INSERT INTO shopcatprodrel(iAboutID, iCategoryID) VALUES(?,?)";
                    $db->_query($strInsert, $params);
                }
            }
            header("location: ?mode=details&iAboutID=" . $iAboutID);

            break;


        case "DELETE":
            $obj = new About();
            $id = filter_input(INPUT_GET, "iAboutID", FILTER_VALIDATE_INT);
            $obj->DeleteAbout($id);
            header("Location: ?mode=list");
            break;


        case "SAVE":
            $id = filter_input(INPUT_POST, "iAboutID", FILTER_SANITIZE_NUMBER_INT);

            $obj = new About();

            showme($_POST);
            if ($id > 0) {
                //Runs $obj->DeleteEvent on load
                $obj->UpdateAbout($id);
                //redicrects to Mode list after running DeleteEvent
            } else {
                //Runs $obj->DeleteEvent on load
                $obj->CreateAbout($id);
                //redicrects to Mode list after running DeleteEvent
            }
            header("location: ?mode=list");
            break;

    }
} else {
    header("location: /cms/Calendar.php");
}
