<?php

class SearchObject
{

    public $param;
    public $SearchCondition;
    public $OrderCondition;

    public function __construct()
    {
        $this->param = array();
        $this->SearchCondition = "";
        $this->OrderCondition = "";
    }

    public function setCondition($value)
    {
        $this->SearchCondition = $value;
    }

    public function pushCondition($value)
    {
        $this->SearchCondition .= " $value";
    }

    public function setOrder($orderValue)
    {
        $this->OrderCondition = " ORDER BY $orderValue";
    }

    public function pushOrder($orderValue)
    {
        if (strlen($this->OrderCondition) == 0)
        {
            $this->OrderCondition = " ORDER BY $orderValue";
        }
        else
        {
            $this->OrderCondition .= ", $orderValue";
        }
    }

    public function pushParam($value)
    {
        $this->param[] = $value;
    }

    public function pushParams($params)
    {
        if ($params != null && is_array($params))
        {
            $arc = count($params);
            for ($i = 0; $i < $arc; $i++)
            {
                $this->param[] = $params[$i];
            }
            //array_merge($this->param, $param);
        }
    }

    public function pushParamLike($value, $left = '%', $right = '%')
    {
        $v = str_replace("%", "", $value);
        $v = str_replace("_", "", $v);
        $this->param[] = $left . $v . $right;
    }

    public function ClearSearch()
    {
        unset($this->param);
        $this->param = array();
        $this->SearchCondition = "";
        $this->OrderCondition = "";
    }

    public function CanSearch()
    {
        return count($this->param) > 0;
    }

}
