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
            $user = new user();
            $user->GetList();

            $iUserID = filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);
            ?>
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <h4>Bruger Table</h4>
                        <a class="btn btn-success" href="user.php?mode=Edit&iUserID=-1">Opret ny bruger!</a>
                        <div class="table-responsive">

                            <h3 style="text-align: center"><?php if (isset($_GET["error"]) != null) {
                                    echo $_GET["error"];
                                } ?>
                            </h3>
                            <table class="table table-bordred table-striped">
                                <?php
                                $list = new listpresenter();
                                $list->ListMaker($user->arrLabels, $user->unset, $user->arrValues, "iUserID");
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
        require_once (DOCROOT . "/cms/assets/incl/nav.php");
            $user = new user();

            $iUserID = filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);
            $user->getDetails($iUserID);


            ?>
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <h4>Bruger Details</h4>
                        <?php
                        echo $arrButtonPanel[] = getButtonLink("plus", "?mode=list", "Oversigt", "btn");
                        echo $arrButtonPanel[] = getButtonLink("plus", "?mode=edit&iUserID=" . $iUserID, "Rediger", "btn");
                        echo $arrButtonPanel[] = getButtonLink("plus", "?mode=SETUSERGROUPS&iUserID=" . $iUserID, "Vælg Grupper", "btn");

                        ?>
                        <div class="table-responsive">


                            <table class="table table-bordred table-striped">
                                <?php
                                $list = new listpresenter();
                                $list->MakeDetails($user->arrLabels, $user->unset, $user->arrValues);
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

        case "EDIT":
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");
            $user = new user();
            $fields = $user->arrFormElm;
            $iUserID = filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);
            $select = $user->GetSelect();


            $image = new image();
            $selectimg = $image->GetSelect();


            ?>
            <div class="container">
                <div class="row">


                    <div class="col-md-12">


                        <?php
                        if ($iUserID == -1) {
                            ?>
                            <h4>Opret bruger!</h4>
                            <?php
                        } else {
                            ?>
                            <h4>Rediger bruger!</h4>
                            <?php
                        }
                        //get the item ID;
                        if ($iUserID == 0 || $iUserID == "-1") {
                        } else {

                            $user->getuser($iUserID);
                            unset($fields["vcPassword"]);
                        }

                        $form = new FormPresenter();
                        $form->MakeForm($fields, $user, $selectimg, "user", false, false, false, true);


                        ?>
                    </div>
                </div>
            </div>

            <?php

            require_once (DOCROOT . "/cms/assets/incl/footer.php");
            break;


        case
        "SETUSERGROUPS":
            $iUserID = filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);
            $strModuleMode = "Bruger Grupper";
            require_once (DOCROOT . "/cms/assets/incl/header.php");
        require_once (DOCROOT . "/cms/assets/incl/nav.php");

            $arrSelected = array();
            $params = array($iUserID);
            $strSelect = "SELECT iGroupID FROM usergrouprel WHERE iUserID = ?";
            $rel = $db->_fetch_array($strSelect, $params);
            foreach ($rel as $value) {
                $arrSelected[] = $value["iGroupID"];
            }

            $group = new usergroup();
            $rows = $group->GetSelect();

            $arrFormValues = array();
            $arrFormValues["iUserID"] = $iUserID;

            ?>

            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <form method="POST">
                            <input type='hidden' name='mode' value='saveusergroups'>
                            <?php
                            $arrButtonPanel = array();
                            $arrButtonPanel[] = getButtonLink("plus", "?mode=list", "Oversigt", "btn");
                            echo textPresenter::presentMode($strModuleName, $strModuleMode, $arrButtonPanel);

                            foreach ($rows as $key => $arrValues) {
                                $field = $arrValues["iGroupID"];
                                $arrFormValues[$field] = in_array($arrValues["iGroupID"], $arrSelected) ? 1 : 0;
                                $strIsChecked = $arrFormValues[$field] ? "checked=\"checked\"" : "";
                                ?>
                                <div class="col-md-12 form-group">
                                    <label>
                                        <?php echo $arrValues["vcGroupName"] ?>
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


        case "SAVEUSERGROUPS":
            showme($_POST);

            $iUserID = Filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);
            $params = array($iUserID);
            $strDelete = "DELETE FROM usergrouprel WHERE iUserID = ?";
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
                    $params = array($iUserID, $value);
                    $strInsert = "INSERT INTO usergrouprel(iUserID, iGroupID) VALUES(?,?)";
                    $db->_query($strInsert, $params);
                }
            }
            header("location: ?mode=details&iUserID=" . $iUserID);

            break;


        case "DELETE":
            $obj = new user();
            $id = filter_input(INPUT_GET, "iUserID", FILTER_VALIDATE_INT);
            //Runs $obj->DeleteUser on load
            $obj->DeleteUser($id);
            //redicrects to Mode list after running DeleteUser
            header("location: ?mode=list");

            break;
        case "SAVE":
            $id = filter_input(INPUT_POST, "iUserID", FILTER_SANITIZE_NUMBER_INT);


            if ($id > 0) {
                showme($_POST);

                $obj = new user();
                //Runs $obj->DeleteUser on load
                $obj->UpdateUser($id);
                //redicrects to Mode list after running DeleteUser
                header("location: ?mode=list");
            }
            if ($id == -1) {

                $obj = new user();
                showme($obj->CreateUser($id));
            exit();
                //Runs $obj->DeleteUser on load
                $obj->CreateUser($id);
                //redicrects to Mode list after running DeleteUser


                header("location: ?mode=list&error=" . $obj->errormsg);
            }
            break;
    }
} else {
    header("location: /cms/index.php");
}
