<?php
session_start();
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public_html/cms/assets/incl/init.php");
}
$mode = setMode();
$strModuleName = "UserGroup";
if ($auth->user->sysadmin || $auth->user->admin) {
    switch (strtoupper($mode)) {

        case "LIST";
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));

            $strModuleMode = "Oversigt";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");
            $Group = new usergroup();
            $Group->GetList();

            $iGroupID = filter_input(INPUT_GET, "iGroupID", FILTER_SANITIZE_NUMBER_INT);

            ?>
            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();

                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);

                    ?>

                    <div class="col-md-12">
                        <a class="btn btn-success" href="usergroup.php?mode=Edit&iUserID=-1">Opret ny Bruger rolle!</a>
                        <div class="table-responsive">

                            <table class="table table-bordred table-striped">
                                <?php
                                $p = new listpresenter();
                                $p->ListMaker($Group->arrLabels, $Group->unset, $Group->arrValues, "iGroupID");
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

            $iGroupID = filter_input(INPUT_GET, "iGroupID", FILTER_SANITIZE_NUMBER_INT);
            $strModuleMode = "Detaljer";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");            $Group = new usergroup();
            $Group->getDetails($iGroupID);
            ?>

            <div class="container">
                <div class="row">
                    <?php
                    $arrButtonPanel = array();
                    $arrButtonPanel[] = getButtonLink("table", "?mode=list", "Oversigt", "btn-primary");
                    $arrButtonPanel[] = getButtonLink("pencil", "?mode=edit&iGroupID=" . $iGroupID, "Rediger side", "btn-success");
                    echo textpresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);
                    ?>
                    <div class="col-md-12">
                        <h4>Bruger Details</h4>
                        <div class="table-responsive">


                            <table class="table table-bordred table-striped">
                                <?php
                                $list = new listpresenter();
                                $list->MakeDetails($Group->arrLabels, $Group->unset, $Group->arrValues);
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

            $Group = new usergroup();
            $fields = $Group->arrFormElm;
            $iGroupID = filter_input(INPUT_GET, "iGroupID", FILTER_SANITIZE_NUMBER_INT);
            $iParentID = filter_input(INPUT_GET, "iParentID", FILTER_SANITIZE_NUMBER_INT, getDefaultValue(-1));


            //Makes $select into image parameter!

            require_once (DOCROOT . "/cms/assets/incl/header.php");
            require_once (DOCROOT . "/cms/assets/incl/nav.php");            $strModuleMode = ($iGroupID > 0) ? "Rediger" : "Opret ny side";


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
                        if ($iGroupID == -1) {
                            ?>
                            <h4>Opret bruger!</h4>
                            <?php
                        } else {
                            ?>
                            <h4>Rediger bruger!</h4>
                            <?php
                        }

                        //get the item ID;
                        if ($iGroupID > 0) {
                            $Group->getGroup($iGroupID);
                        }

                        $form = new formpresenter();
                        $form->MakeForm($fields, $Group, null, false, "UserGroup", false, true, false, false);
                        ?>
                    </div>
                </div>
            </div>

            <?php
            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;

        case "DELETE":
            $obj = new usergroup();
            $id = filter_input(INPUT_GET, "iGroupID", FILTER_VALIDATE_INT);
            $obj->delete($id);
            header("Location: ?mode=list");
            break;


        case "SAVE":
            $id = filter_input(INPUT_POST, "iGroupID", FILTER_SANITIZE_NUMBER_INT);

            $obj = new usergroup();

            showme($_POST);
            if ($id > 0) {
                //Runs $obj->DeleteEvent on load
                $obj->UpdateObj($id);
                //redicrects to Mode list after running DeleteEvent
            }
            if ($id = -1) {
                //Runs $obj->DeleteEvent on load
                $obj->CreateObj($id);
                //redicrects to Mode list after running DeleteEvent
            }
            header("location: ?mode=list");
            break;

    }
} else {
    header("location: /cms/index.php");
}
