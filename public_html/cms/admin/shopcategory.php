<?php
session_start();
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
}

$mode = setMode();
$strModuleName = "Category";
if ($auth->user->sysadmin || $auth->user->admin) {
    switch (strtoupper($mode)) {

        default: "LIST";
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));

            $strModuleMode = "Oversigt";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");
            $Category = new shopCategory();
            $Category->GetList();

            $iCategoryID = filter_input(INPUT_GET, "iCategoryID", FILTER_SANITIZE_NUMBER_INT);

            ?>
            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);

                    ?>

                    <div class="col-md-12">
                        <h4>Category Table</h4>
                        <a class="btn btn-success" href="shopcategory.php?mode=Edit&iCategoryID=-1">Opret nyt Category!</a>
                        <div class="table-responsive">

                            <table class="table table-bordred table-striped">
                                <?php
                                $p = new listPresenter();
                                $p->ListMaker($Category->arrLabels, $Category->unset, $Category->arrValues, "iCategoryID");
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

        case
        "DETAILS";
            $iCategoryID = filter_input(INPUT_GET, "iCategoryID", FILTER_SANITIZE_NUMBER_INT);
            $strModuleMode = "Detaljer";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
            require_once (DOCROOT . "/cms/assets/incl/nav.php");            $Category = new shopCategory();
            $Category->getDetails($iCategoryID);
            ?>

            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    $arrButtonPanel[] = getButtonLink("table", "?mode=list", "Oversigt", "btn-primary");
                    $arrButtonPanel[] = getButtonLink("pencil", "?mode=edit&iCategoryID=" . $iCategoryID, "Rediger side", "btn-success");

                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);
                    ?>
                    <div class="col-md-12">
                        <h4>Category Details</h4>
                        <div class="table-responsive">


                            <table class="table table-bordred table-striped">
                                <?php
                                $list = new ListPresenter();
                                $list->MakeDetails($Category->arrLabels, $Category->unset, $Category->arrValues);
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

            $Category = new shopcategory();
            $fields = $Category->arrFormElm;
            $iCategoryID = filter_input(INPUT_GET, "iCategoryID", FILTER_SANITIZE_NUMBER_INT);
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));


            //Makes $select into image parameter!
            $image = new image();
            $selectimg = $image->GetSelect();

            //makes $selectcat to make category selectbox
            $selectcat = $Category->GetSelect();

            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");            $strModuleMode = ($iCategoryID > 0) ? "Rediger" : "Opret ny side";


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
                        if ($iCategoryID == -1) {
                            ?>
                            <h4>Opret Category!</h4>
                            <?php
                        } else {
                            ?>
                            <h4>Rediger Category!</h4>
                            <?php
                        }

                        //get the item ID;
                        if ($iCategoryID > 0) {
                            $Category->getCategory($iCategoryID);
                        }

                        $form = new formpresenter();
                        $form->MakeForm($fields, $Category, $selectimg, $selectcat, "shopcategory", false, false, true, true);
                        ?>
                    </div>
                </div>
            </div>

            <?php
            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;


        case "DELETE":
            $obj = new shopCategory();
            $id = filter_input(INPUT_GET, "iCategoryID", FILTER_VALIDATE_INT);
            $obj->delete($id);
            header("Location: ?mode=list");
            break;


        case "SAVE":
            $id = filter_input(INPUT_POST, "iCategoryID", FILTER_SANITIZE_NUMBER_INT);

            $obj = new shopcategory();

            showme($_POST);
            if ($id > 0) {
                //Runs $obj->DeleteEvent on load
                $obj->UpdateObj($id);
                //redicrects to Mode list after running DeleteEvent
            }
            if ($id == -1) {

                //Runs $obj->DeleteEvent on load
                $obj->CreateObj($id);
                //redicrects to Mode list after running DeleteEvent
            }
            header("location: ?mode=list");
            break;

    }
} else {
    header("location: /cms/Calendar.php");
}
