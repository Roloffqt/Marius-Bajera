<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 09-12-2016
 */
class textpresenter
{

    static function presentMode($strModuleName, $strModuleMode, $arrButtonPanel)
    {
        $accHtml = '<div class="row">';
        $accHtml .= "<div class='col-sm-12'>";
        $accHtml .= "<h3 class='margin-top'>" . $strModuleName . "</h3>\n";
        $accHtml .= "<h4>" . $strModuleMode . "</h4>\n";
        $accHtml .= "</div>\n";

        if (isset($arrButtonPanel) && count($arrButtonPanel) > 0) {
            $accHtml .= "<div class='buttonpanel'>\n";
            foreach ($arrButtonPanel as $key => $value) {
                $accHtml .= $value;
            }
            $accHtml .= "</div>\n";
        }
        $accHtml .= "</div>\n";
        return $accHtml;
    }

}