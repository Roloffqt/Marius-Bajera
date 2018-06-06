<?php

/**
 * Created by PhpStorm.
 * Auther @ Mads Roloff - Rights reservede to Author
 * Date: 19-05-2017
 */
class listpresenter
{

    public $arrLabels;
    public $arrValues;
    private $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;

        //$this->arrLabels = $arrLabels;
        //$this->arrValues = $arrValues;
    }

    public function ListMaker($arrLabels, $unset, $arrValues, $ID, $edit = true, $delete = true)
    {
        echo "<thead>";
        foreach ($arrLabels as $key => $value) {
            if (!in_array($value["Field"], $unset)) {

                echo "<th>" . $value["Comment"] . "</th>";
            }
        }

        if (isset($arrValues[0]["iIsActive"])) {
            echo "<th>Active</th>";
        }
        if ($edit == true) {
            echo "<th>Edit</th>";
        }
        echo "<th>Details</th>";
        if ($delete == true) {
            echo "<th>Delete</th>";
        }
        echo "</thead>
         <tbody>";
        foreach ($arrValues as $key => $array_values) {
            echo "<tr>";
            foreach ($arrLabels as $key => $value) {

                //IF Columns is in $unset ignore it!

                if (!in_array($value["Field"], $unset)) {
                    echo "<td>" . $array_values[$value["Field"]] . "</td>";
                }
            }


            if (isset($array_values["iIsActive"])) {
                echo '<td>';
                if ($array_values["iIsActive"] > 0) {
                    echo "<i class='fa fa-check'></i>";
                } else {
                    echo "<i class='fa fa-times'></i>";
                }
                echo '</td>';
            }
            if ($edit == true) {
                echo '<td>
                <p data-placement="top" data-toggle="tooltip" title="Edit">
                    <a href="?mode=EDIT&' . $ID . '=' . $array_values["$ID"] . '" class="btn btn-primary no-border-radius btn-xs"><span class="fa fa-pencil"></span></a>
                </p>
            </td>';
            }

            echo '
            <td>
                <p data-placement="top" data-toggle="tooltip" title="Details">
                    <a href="?mode=DETAILS&' . $ID . '=' . $array_values["$ID"] . '" class="btn btn-success btn-xs"><span class="fa fa-eye"></span></a>
                </p>
            </td>';
            if ($delete == true) {
                echo '<td>
                <p data-placement="top" data-toggle="tooltip" title="Delete">
                    <a href="?mode=DELETE&' . $ID . '=' . $array_values["$ID"] . '" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span></a>
                </p>
            </td>';
            }
            echo '</tr>';

        }
    }

    public
    function MakeDetails($arrLabels, $unset, $arrValues)
    {

        echo "<th>Detail</th>
         <th>Value</th>
         </thead>
         <tbody>";

        foreach ($arrLabels as $key => $value) {
            foreach ($arrValues as $key => $array_values) {

                if (isset($array_values["iIsActive"])) {
                    if ($array_values["iIsActive"] == 1) {
                        $array_values["iIsActive"] = "<i class='fa fa-check'></i>";
                    } else {
                        $array_values["iIsActive"] = "<i class='fa fa-times'></i>";
                    }
                }
                $array_values["daCreated"] = date("d - F, Y", $array_values["daCreated"]);
                if (!in_array($value["Field"], $unset)) {

                    echo "<tr>\n";
                    echo "<td style='font-weight:600;'>" . $value["Comment"] . "</td>\n";
                    echo "<td style='font-weight:300 ;'>" . $array_values[$value["Field"]] . "</td>\n";
                    echo "</tr>\n";

                }
            }

        }
    }
}