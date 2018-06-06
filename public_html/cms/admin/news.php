<?php
session_start();
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
}
$mode = setMode();
$strModuleName = "news";

if ($auth->user->sysadmin || $auth->user->admin) {
    switch (strtoupper($mode)) {

        case "LIST";
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));

            $strModuleMode = "List of news";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");
            $news = new news();
            $news->GetList();

            $iNewsID = filter_input(INPUT_GET, "iNewsID", FILTER_SANITIZE_NUMBER_INT);

            ?>
            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);

                    ?>

                    <div class="col-md-12">
                        <a class="btn btn-success" href="news.php?mode=Edit&iNewsID=-1">Add new news</a>
                        <div class="table-responsive">

                            <table class="table table-bordred table-striped">
                                <?php
                                $p = new listPresenter();
                                $p->ListMaker($news->arrLabels, $news->unset, $news->arrValues, "iNewsID");
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

            $iNewsID = filter_input(INPUT_GET, "iNewsID", FILTER_SANITIZE_NUMBER_INT);
            $strModuleMode = "Details";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");            $news = new news();
            $news->getDetails($iNewsID);
            ?>

            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    $arrButtonPanel[] = getButtonLink("table", "?mode=list", "List", "btn-primary");
                    $arrButtonPanel[] = getButtonLink("pencil", "?mode=edit&iNewsID=" . $iNewsID, "Edit news", "btn-success");
                    $arrButtonPanel[] = getButtonLink("plus", "?mode=SETCATEGORY&iNewsID=" . $iNewsID, "Choose Category", "btn");
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);
                    ?>
                    <div class="col-md-12">
                        <h4>Produkt Details</h4>
                        <div class="table-responsive">


                            <table class="table table-bordred table-striped">
                                <?php
                                $list = new ListPresenter();
                                $list->MakeDetails($news->arrLabels, $news->unset, $news->arrValues);
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


            $iNewsID = filter_input(INPUT_GET, "iNewsID", FILTER_SANITIZE_NUMBER_INT);
            $news = new news($iNewsID);
                $fields = $news->arrFormElm;
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));


            //Makes $select into image parameter!
            $image = new image();
            $selectimg = $image->GetSelect();

            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");            $strModuleMode = ($iNewsID > 0) ? "Edit" : "Create New news";


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
                        $form->MakeForm($fields, $news, $selectimg, false, "news", false, true, true, false);
                        ?>
                    </div>
                </div>
            </div>

            <?php
            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;

case
        "SETCATEGORY":
            $iNewsID = isset($_GET["iNewsID"]) && is_int(intval($_GET["iNewsID"])) ? $_GET["iNewsID"] : false;


            $strModuleMode = "Category";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");

            $arrSelected = array();
            $params = array($iNewsID);

            $strSelect = "SELECT iCategoryID FROM shopcatprodrel WHERE iNewsID = ?";
            $rel = $db->_fetch_array($strSelect, $params);

            foreach ($rel as $value) {
                $arrSelected[] = $value["iCategoryID"];
            }


            $cate = new shopcategory();
            $rows = $cate->getSelect();


            $arrFormValues = array();
            $arrFormValues["iNewsID"] = $iNewsID;


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

            $iNewsID = Filter_input(INPUT_GET, "iNewsID", FILTER_SANITIZE_NUMBER_INT);
            $params = array($iNewsID);
            $strDelete = "DELETE FROM shopcatprodrel WHERE iNewsID = ?";
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
                    $params = array($iNewsID, $value);
                    $strInsert = "INSERT INTO shopcatprodrel(iNewsID, iCategoryID) VALUES(?,?)";
                    $db->_query($strInsert, $params);
                }
            }
            header("location: ?mode=details&iNewsID=" . $iNewsID);

            break;


        case "DELETE":
            $obj = new news();
            $id = filter_input(INPUT_GET, "iNewsID", FILTER_VALIDATE_INT);
            $obj->Deletenews($id);
            header("Location: ?mode=list");
            break;


        case "SAVE":
            $id = filter_input(INPUT_POST, "iNewsID", FILTER_SANITIZE_NUMBER_INT);

            $obj = new news();

            showme($_POST);
            if ($id > 0) {
                //Runs $obj->DeleteEvent on load
                $obj->Updatenews($id);
                //redicrects to Mode list after running DeleteEvent
            } else {
                //Runs $obj->DeleteEvent on load
                $obj->CreateNews($id);
                //redicrects to Mode list after running DeleteEvent
            }
            header("location: ?mode=list");
            break;

    }
} else {
    header("location: /cms/Calendar.php");
}
