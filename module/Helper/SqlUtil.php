<?php

/*
 * New Sql Utility Class which extends the mysql connector, making it easy to make use of parameters in class
 */

class SqlUtil extends MysqlClass
{

    public static function DropDownSql($Name, $Command, $default = 0, $class = "form-select", $attribute = '')
    {
        $status = false;
        $Connection = new \MysqlClass();
        /* $Command = "SELECT ACCOMSID, AccomStatus FROM accommodationstatus ;"; */
        echo "<select class='$class' id='$Name' name='$Name' $attribute>";

        if ($Connection->query($Command)) {
            while ($DataRow = $Connection->fetchArray()) {
                $Sets = $DataRow[0] == $default ? 'selected="true"' : '';
                echo "<option value='" . $DataRow[0] . "' $Sets>" . $DataRow[1] . "</option>";
            }
            $status = true;
        }
        echo "</select>";

        return false;
    }

    public static function DropDownSqlWithDefault($Name, $Command, $defaultSelect = "All", $class = "form-select", $attribute = '')
    {
        $status = false;
        $Connection = new \MysqlClass();
        /* $Command = "SELECT ACCOMSID, AccomStatus FROM accommodationstatus ;"; */
        echo "<select class='$class' id='$Name' name='$Name' $attribute>";
        echo "<option value='0'>$defaultSelect</option>";
        if ($Connection->query($Command)) {
            while ($DataRow = $Connection->fetchArray()) {

                echo "<option value='" . $DataRow[0] . "'>" . $DataRow[1] . "</option>";
            }
            $status = true;
        }
        echo "</select>";

        return false;
    }

    public function GetRecordsObject($Command, $offset = 0, $limit = 10)
    {
        $offset1 = intval($offset) * intval($limit);
        $object = new JRecordObject();
        $object->data = new ArrayObject();

        if ($this->query($Command . " LIMIT $offset1, $limit")) {
            //$object = new stdClass();
            while ($DataRow = $this->fetchObject()) {
                $object->count++;
                $object->data[] = $DataRow;
            }
        }

        return $object;
    }

    public function GetRecordsObjectAll($Command)
    {
        $object = new JRecordObject();
        $object->data = new ArrayObject();

        if ($this->query($Command)) {
            while ($DataRow = $this->fetchObject()) {
                $object->count++;
                $object->data[] = $DataRow;
            }
        }

        return $object;
    }

    public function GetRecordsArray($Command, $offset = 0, $limit = 10)
    {
        $offset1 = intval($offset) * intval($limit);
        $object = new JRecordObject();
        $object->data = array();

        if ($this->query($Command . " LIMIT $offset1,$limit")) {
            //$object = new stdClass();
            while ($DataRow = $this->fetchArray()) {
                $object->count++;
                $object->data[] = $DataRow;
            }
        }


        return $object;
    }

    public function GetRecordsObjectFunc($Command, $offset = 0, $limit = 10, $functToExecute = null)
    {
        $object = new JRecordObject();
        $object->count = 0;
        $object->page = $offset == 0 ? 0 : $offset / $limit;
        $object->pages = 0;
        $object->limit = $limit;
        $object->data = new ArrayObject();
        $offsetCalc = $offset * $limit;

        if ($functToExecute && function_exists($functToExecute) && $this->query($Command . " LIMIT $offsetCalc,$limit")) {
            //$object = new stdClass();
            while ($DataRow = $this->fetchObject()) {
                $object->count++;
                $functToExecute($DataRow);
                $object->data[] = $DataRow;
            }
        }

        return $object;
    }

    public function LoadDataObject($countQuery, $searchableQuery, $pageLimit, $page, $SearchData = null)
    {
        if (!$SearchData) {
            $SearchData = new SearchObject();
        }

        $CountCommand = "$countQuery $SearchData->SearchCondition";
        $SelectCommand = "$searchableQuery $SearchData->SearchCondition $SearchData->OrderCondition";

        $this->pushParams($SearchData->param);
        $max = $this->queryScalar($CountCommand);
        $result = new JRecordObject();

        if ($max > 0) {
            switch ($pageLimit) {
                case "1":
                case "10":
                case "50":
                case "100":
                    $pageLimit = intval($pageLimit);
                    break;
                default:
                    $pageLimit = 10;
            }


            $count = self::CheckLimit($pageLimit, $max); // Check if the limit we get from the client is valid
            $totalPages = self::GetTotalPages($count, $max); // Get the maximum amount of viewable pages
            $offset = self::CheckPageNo($page, $totalPages - 1); // Check the client offset / page number

            $this->pushParams($SearchData->param);
            $result = $this->GetRecordsObject($SelectCommand, $offset, $count);
            $result->pages = intval($totalPages);
            $result->page = intval($offset);
            $result->items = intval($max);
        }


        $SearchData->ClearSearch();

        return $result;
    }

    public function LoadDataObjectAll($searchableQuery, $SearchData = null)
    {
        if (!$SearchData) {
            $SearchData = new SearchObject();
        }

        $SelectCommand = "$searchableQuery $SearchData->SearchCondition $SearchData->OrderCondition";

        $this->pushParams($SearchData->param);
        $result = $this->GetRecordsObjectAll($SelectCommand);
        $result->pages = 0;
        $result->page = 0;

        $SearchData->ClearSearch();

        return $result;
    }

    public function LoadDataArray($countQuery, $searchableQuery, $pageLimit, $page, $SearchData = null)
    {
        if (!$SearchData) {
            $SearchData = new SearchObject();
        }

        $CountCommand = "$countQuery $SearchData->SearchCondition";
        $SelectCommand = "$searchableQuery $SearchData->SearchCondition $SearchData->OrderCondition";

        $this->pushParams($SearchData->param);

        $max = $this->queryScalar($CountCommand);
        $result = new JRecordObject();

        if ($max > 0) {
            switch ($pageLimit) {
                case "1":
                case "10":
                case "50":
                case "100":
                    $pageLimit = intval($pageLimit);
                    break;
                default:
                    $pageLimit = 10;
            }


            $count = self::CheckLimit($pageLimit, $max); // Check if the limit we get from the client is valid
            $totalPages = self::GetTotalPages($count, $max); // Get the maximum amount of viewable pages
            $offset = self::CheckPageNo($page, $totalPages - 1); // Check the client offset / page number

            $this->pushParams($SearchData->param);
            $result = $this->GetRecordsArray($SelectCommand, $offset, $count);
            $result->pages = intval($totalPages);
            $result->page = intval($offset);
            $result->items = intval($max);
        }


        $SearchData->ClearSearch();

        return $result;
    }


    private function CheckLimit($current, $max)
    {

        $min = 10;
        if (ValidationClass::ValidateFullNumber($current) && $current >= 0) {
            $min = $current;
        }

        if ($min > $max) {
            $min = $max;
        }

        return $min;
    }

    private function CheckPageNo($page, $totalPages)
    {

        if (!ValidationClass::ValidateFullNumber($page)) {
            $page = 0;
        }

        $pageout = $page;
        if ($page < 0) {
            $pageout = 0;
        } else if ($page > $totalPages) {
            $pageout = $totalPages;
        }

        return $pageout;
    }

    private function GetTotalPages($limit, $totalRecords)
    {

        $remain = $totalRecords % $limit;

        if ($remain > 0) {
            $pageMax = (($totalRecords - $remain) / $limit) + 1;
        } else {
            $pageMax = ($totalRecords / $limit);
        }
        return $pageMax;
    }

}