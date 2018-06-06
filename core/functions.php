<?php

function sysHeader()
{
    global $db;
    require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/assets/incl/header.php";
}

function sysFooter()
{
    global $db;
    require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/assets/incl/footer.php";
}

function sysBackendHeader()
{
    global $db;
    require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/header.php";
}

function sysBackendFooter()
{
    global $db;
    require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/footer.php";
}


// Converts a bool value to FA check/ban icon
function boolToIcon($val)
{
    $strIcon = ($val > 0) ? "check" : "ban";
    return "<span class=\"testing fa fa-" . $strIcon . "\"></span\n";
}


function getIcon($strUrl, $strIcon, $strTitle = "", $strScript = "", $strClass = "")
{
    $attrClass = (!empty($strClass)) ? $strClass : "icon";
    $attrEvent = (!empty($strScript)) ? "onclick=\"" . $strScript . "\"" : "";
    $attrHref = (!empty($strUrl) && empty($strScript)) ? $strUrl : "Javascript:void(0)";
    $strHtml = "<a href=\"" . $attrHref . "\" " . $attrEvent . ">";
    $strHtml .= "<span class=\"" . $attrClass . " fa fa-" . $strIcon . "\" title=\"" . $strTitle . "\"></span>\n";
    if (!empty($strUrl) || !empty($strScript)) {
        $strHtml .= "</a>\n";
    }
    return $strHtml;
}

function onlyLetters($string)
{
    return preg_replace("/[^a-zA-Z]+$/", "", $string);
}

//Creates a buttom with parameters for "class", "text" and "url"
function getButtonLink($strIcon, $strUrl = "#", $strText = "Untitled", $strClass = "btn-default")
{
    $strHtml = "<a href=" . $strUrl . " class='btn' " . $strClass . "><i class='fa fa-'" . $strIcon . "></i>" . $strText . " </a>\n";
    return $strHtml;
}

/**
 * Writes out a button
 * @author Heinz K, Tech College Dec. 2016
 * @param string $type The type of the button
 * @param string $value The text value of the button
 * @param string $event (Optional) A click event call of the button
 * @param string $class (Optional) Additional class name for the button
 * @param string $data (Optional) A data attribute for the button
 * @return string Returns a html string with the button element
 */
function getButton($type, $value, $event = "", $class = "btn-default", $data = "")
{
    $event = !empty($event) ? " onclick=\"" . $event . "\"" : "";
    $data = !empty($data) ? $data : "";
    $strHtml = "<button type=\"" . $type . "\" class=\"btn " . $class . "\" " . $event . " " . $data . ">" . $value . "</button>\n";
    return $strHtml;
}

/**
 * Builds a select box with name, options and selected value
 * Optional settings for multiple and event features
 * @param string $strName Name/Id attribute on selectbox
 * @param array $arrOptions Multi-dimensional array of options
 *          (key = numeric, value = array(option-value, option-text))
 * @param var $value Value to be selected (Optional)
 * @param bool $isMultiple Set to true if multple select
 * @param string $strOnChangeEvent Set onchange event call if needed (Ex: doSomething())
 * @return string Returns a html string with a select box
 */
function SelectBox($strName, $arrOptions, $value = NULL, $isMultiple = FALSE, $strOnChangeEvent = '')
{
    $strAttrName = ($isMultiple === TRUE) ? $strName . "[]" : $strName;
    $strMultiple = ($isMultiple === TRUE) ? "multiple" : "";
    $strEvent = (!empty($strOnChangeEvent)) ? "onchange=\"" . $strOnChangeEvent . "\"" : "";

    $strHtml = "<select class=\"form-control\" id=\"" . $strName . "\" " .
        "name=\"" . $strAttrName . "\" " . $strMultiple . " " . $strEvent . ">\n";

    foreach ($arrOptions as $arrOptionInfo) {
        $arrOptionInfo = array_values($arrOptionInfo);

        $selected = ($isMultiple === TRUE) ?
            in_array($arrOptionInfo[0], $value) ? "selected" : "" :
            ($value == $arrOptionInfo[0]) ? "selected" : "";

        $strHtml .= "<option value=\"" . $arrOptionInfo[0] . "\" " . $selected . ">" . $arrOptionInfo[1] . "</option>\n";
    }
    $strHtml .= "</select>\n";
    return $strHtml;
}

/**
 * Displays a read friendly var_dump
 * @param array $array
 * @param int $view
 */
function showme($array, $view = 0)
{
    print ($view > 0) ? "<xmp>\n" : "<pre>\n";
    var_dump($array);
    print ($view > 0) ? "\n</xmp>" : "\n</pre>";
}

/**
 * Gets page mode from GET or POST - otherwise return default
 * @param string $default
 * @return string Returns selected string
 */
function setMode($default = "works")
{
    $mode = filter_input(INPUT_POST, "mode", FILTER_SANITIZE_STRING);
    if (empty($mode)) {
        $mode = filter_input(INPUT_GET, "mode", FILTER_SANITIZE_STRING);
    }
    if (empty($mode)) {
        $mode = $default;
    }
    return $mode;
}



/**
 * Gets page mode from GET or POST - otherwise return default
 * @param string $default
 * @return string Returns selected string
 */
function setPage($default = "1")
{
    $mode = filter_input(INPUT_POST, "cate", FILTER_SANITIZE_STRING);
    if (empty($mode)) {
        $mode = filter_input(INPUT_GET, "cate", FILTER_SANITIZE_STRING);
    }
    if (empty($mode)) {
        $mode = $default;
    }
    return $mode;
}

function setEventType($default = "new")
{
    $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_STRING);
    if (empty($type)) {
        $type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_STRING);
    }
    if (empty($type)) {
        $type = $default;
    }
    return $type;
}


function getAbbr($str)
{
    $res = preg_split('/(?=[A-Z])/', $str);
    return reset($res);

}

function makeStamp($strElm)
{
    $arrFormats = array("day", "month", "year", "hours", "minutes");
    $arrDate = array();

    foreach ($arrFormats as $value) {
        $arrDate[$value] = filter_input(INPUT_POST, $strElm . "_" . $value, FILTER_SANITIZE_NUMBER_INT, getDefaultValue(0));
    }
    return mktime($arrDate["hours"], $arrDate["minutes"], 0, $arrDate["month"], $arrDate["day"], $arrDate["year"]);
}

function getDefaultValue($val)
{
    $options = array('options' => array('default' => $val));
    return $options;
}

/**
 * Event boxes
 *  use EVENT DB
 */
function frontListEvent()
{
    $event = new event();
    $rows = $event->GetSelect(3);
    $event->key = filter_input(INPUT_POST, "iEventID", FILTER_VALIDATE_INT);

    $accHtml = "<div class=\"row\">\n";

    $accHtml .= "<h2 style='color:#5bc0de;'>UPCOMING EVENTS</h2>";
    foreach ($rows as $key => $row) {
        $accHtml .= "<div class=\"col-lg-11 redbox-hack\">\n";

        $accHtml .= "<hr>";
        $accHtml .= "<div class='col-lg-1'>";
        $accHtml .= "<h2 class=\"redbox-date\">" . date("j", $row["daCreated"]) . " </h2>\n";
        $accHtml .= "<h4 class=\"redbox-date\">" . date("M", $row["daCreated"]) . " </h4>\n";

        $accHtml .= "</div>";
        $accHtml .= "<div class=\"col-lg-9 red-box redbox-height event-desc\">\n";
        $accHtml .= "<p class=\"redbox-font\">" . $row["vcTitle"] . "</p>\n";
        $accHtml .= "<p class=\"redbox-desc\">" . date("D", $row["daCreated"]) . " | " . date("g:i", $row["daCreated"]) . " </p>\n";
        $accHtml .= "</div>\n";

        $accHtml .= "<div class=\"col-lg-2 red-box redbox-height event-desc\">\n";
        $accHtml .= "<a href='#' class='btn btn-info'>More</a>\n";
        $accHtml .= "</div>\n";

        $accHtml .= "</div>\n";


    }

    $accHtml .= "</div>\n";
    echo $accHtml;
}

function getEventNews()
{
    $accHtml = "";
    $news = new news();
    $rows = $news->getAllNews();
    $news->key = filter_input(INPUT_POST, "iNewsID", FILTER_VALIDATE_INT);

    foreach ($rows as $key => $row) {
        $accHtml = "<div class='col-lg-offset-1 col-lg-10'>";
        $accHtml .= "<p class='news-text'>" . $row["txContent"] . "</p>";
        $accHtml .= "</div>";
    }
    echo $accHtml;
}

function GetArtist($iArtistID)
{
    $questions = array(
        "Name", "Age", "Description", "City", "Education", "About"
    );

    $gallery = new gallery();
    $rows = $gallery->getArtist($iArtistID);
    $gallery->key = filter_input(INPUT_POST, "iNewsID", FILTER_VALIDATE_INT);

    $accHtml = "<div class=\" col-lg-5 col-lg-offset-3 \">";
    foreach ($rows as $key => $row) {
        $accHtml .= "<ul style='font-size: 18px !important;'> <span class='QuesitonsHeading'> " . $questions[5] . "</span>";
        $accHtml .= "<li><span class='Quesitons'>" . $questions[0] . "</span>: " . $row["vcArtistName"] . "</li>";
        $accHtml .= "<li><span class='Quesitons'>" . $questions[1] . "</span>: " . $row["vcArtistAge"] . "</li>";
        $accHtml .= "<li><span class='Quesitons'>" . $questions[3] . "</span>: " . $row["vcArtistCity"] . "</li>";
        $accHtml .= "<li><span class='Quesitons'>" . $questions[4] . "</span>: " . $row["vcArtistEducation"] . "</li>";
        $accHtml .= "<li><span class='Quesitons'>" . $questions[2] . "</span>: " . $row["txArtistDesc"] . "</li>";
        $accHtml .= "</ul>";
    }
    $accHtml .= "</div>";
    echo $accHtml;
}


function GetImage()
{
    $gallery = new gallery();
    $rows = $gallery->getAllImages();
    $gallery->key = filter_input(INPUT_POST, "iImagesID", FILTER_VALIDATE_INT);
    $accHtml = "<div class=\"gallery row\">\n";
    foreach ($rows as $key => $row) {
        $accHtml .= "<figure class=\"col-lg-3\">\n";
        $accHtml .= "<div>\n";
        $accHtml .= "<div style=\"background-image: url(" . $row["vcImageUrl"] . " )\"></div>\n";
        $accHtml .= "</div>\n";
        $accHtml .= "</figure>\n";
    }
    $accHtml .= "</div>";
    echo $accHtml;
}

function GetArtistImage($iArtistID)
{
    $gallery = new gallery();
    $rows = $gallery->getArtistImages($iArtistID);
    $gallery->key = filter_input(INPUT_POST, "iImagesID", FILTER_VALIDATE_INT);
    $accHtml = "<div class=\"gallery row\">\n";
    foreach ($rows as $key => $row) {
        $accHtml .= "<figure class=\"col-lg-3\">\n";
        $accHtml .= "<div>\n";
        $accHtml .= "<div style=\"background-image: url(" . $row["vcImageUrl"] . " )\"></div>\n";
        $accHtml .= "</div>\n";
        $accHtml .= "</figure>\n";
    }
    $accHtml .= "</div>";
    echo $accHtml;
}

function GetEventTab($Mapsapi)
{
    $event = new event();
    $rows = $event->getAll();
    $event->key = filter_input(INPUT_POST, "iEventID", FILTER_VALIDATE_INT);
    $i = 2;
    $ie = 2;
    $accHtml = "";

    $accHtml .= "<ul class=\"nav nav-pills\">\n";
    //$accHtml .= "<li class=\"\">\n<a href=\"#1a\" data-toggle=\"tab\">Default</a>\n</li>\n";
    foreach ($rows as $key => $row) {
        $accHtml .= "<li>\n<a href=\"#" . $i++ . "a\" data-toggle=\"tab\">" . $row["vcTitle"] . " -     d" . date("j", $row["daStart"]) . "/" . date("M", $row["daStart"]) . "</a>\n</li>\n";
    }
    $accHtml .= "</ul>\n";
    $accHtml .= "<div class=\"tab-content clearfix\">\n";
    //  $accHtml .= "<div id=\"1a\" class=\"tab-pane\">\n<h3>Default</h3>\n</div>";
    foreach ($rows as $key => $row) {
        $accHtml .= "<div id=\"" . $ie++ . "a\" class=\"tab-pane\">\n";
        $accHtml .= "<div class='col-lg-12'>";
        $accHtml .= "<h3 style='display: inline-block'>" . $row["vcTitle"] . "</h3>\n";
        $accHtml .= "<p style='margin-left: 10px;display: inline-block'>" . date("m/d/y", $row["daStart"]) . " - </p>\n";
        $accHtml .= "<p style='display: inline-block'>" . date("m/d/y", $row["daStop"]) . "</p>\n";
        $accHtml .= "</div>";
        $accHtml .= "<div class='col-lg-6'>";
        $accHtml .= "<p>" . $row["txLongDesc"] . "</p>";
        $accHtml .= "<p>" . $row["iVenueID"] . "</p>";
        $accHtml .= "<p><a href='" . $row["vcTicketUrl"] . " '> " . $row["vcTicketFriendlyDesc"] . " </a></p>";
        $accHtml .= "</div>";
        $accHtml .= "<div class='col-lg-6'>";
        $accHtml .= "<iframe id='map' width=\"600\"height=\"350\" frameborder=\"0\" style=\"border:0\"src=\"https://www.google.com/maps/embed/v1/place?key=" . $Mapsapi . "&q=Space+Needle,Seattle+WA\" allowfullscreen></iframe>";
        $accHtml .= "</div>\n";
        $accHtml .= "</div>\n";
    }
    $accHtml .= "</div>\n";

    echo $accHtml;

}

function showBoardmembers()
{
    $board = new boardmember();
    $rows = $board->getAll();
    $board->key = filter_input(INPUT_POST, "iUserID", FILTER_VALIDATE_INT);

    $accHtml = "";

    $arrNum = array("One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten");
    $i = 1;


    foreach ($rows as $key => $row) {
        $accHtml .= "<div class=\"col-md-6\" href=\"#" . $arrNum[$i++] . "!\">\n";

        $accHtml .= "<div class=\"col-md-12 board-info\">\n\t";
        $accHtml .= "<img src=\"http://placehold.it/250\" class=\"img-responsive\" alt=\"Board-members\" draggable=\"false\"/>\n\t";
        $accHtml .= "<p>" . $row["vcRole"] . "</p>\n\t";
        $accHtml .= "<p style='text-transform: uppercase;'><strong>" . $row["vcFirstName"] . " " . $row["vcLastName"] . "</strong></p>\n\t";
        $accHtml .= "<p>" . $row["vcEmail"] . "</p>\n\t";
        $accHtml .= "</div>\n";

        $accHtml .= "</div>\n";
    }
    echo $accHtml;
}
