<?php

class MysqlClass
{

    private $sqlCon = NULL;
    private $stm = NULL;
    private $lastID_safe = 0;
    private $param;
    private $namedParam;

    public function __construct($UseDefault = true)
    {
        if ($UseDefault)
        {
            $this->setConnection($GLOBALS['config']->DBServer, $GLOBALS['config']->DBName, $GLOBALS['config']->DBUser, $GLOBALS['config']->DBPass);
        }
    }

    public function setConnection($Host = '127.0.0.1', $DatabaseName = '', $UserName = '', $Password = '')
    {
        try
        {
            $this->sqlCon = new PDO('mysql:host=' . $Host . ';dbname=' . $DatabaseName . ';charset=utf8mb4', $UserName, $Password);
            $this->sqlCon->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $this->sqlCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->param = array();
            $this->namedParam = array();
            $this->stm = new PDOStatement();
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error, setConnection", $ex, "", "");
        }
    }

    public function OutScript($query)
    {
        $que = $query;
        if ($this->param != null && count($this->param) > 0)
        {

            for ($i = 0; $i < count($this->param); $i++)
            {
                $pos = strpos($que, "?");

                if ($pos == -1)
                {
                    break;
                }
                $que = substr($que, 0, $pos) . "'" . $this->param[$i] . "'" . substr($que, $pos + 1);
            }
        }

        return $que;
    }

    public function pushParam($value, $paramName = '')
    {
        if (empty($paramName))
        {
            $this->param[] = $value;
        }
        else
        {
            $this->namedParam[$paramName] = $value;
        }
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
        }
    }

    public function pushParamLike($value, $paramName = '', $left = '%', $right = '%')
    {
        $v = str_replace("%", "", $value);
        $v = str_replace("_", "", $v);


        if (empty($paramName))
        {
            $this->param[] = $left . $v . $right;
        }
        else
        {
            $this->namedParam[$paramName] = $left . $v . $right;
        }
    }

    public function clearParam()
    {
        unset($this->param);
        $this->param = array();

        unset($this->namedParam);
        $this->namedParam = array();
    }

    public function getLastInsertId(): int
    {
        try
        {
            if ($this->sqlCon != null)
            {
                return $this->sqlCon->lastInsertId();
            }
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }
        return -1;
    }

    public function affectedRows()
    {
        $result = -1;
        try
        {
            if ($this->stm != null)
            {
                $result = $this->stm->rowCount();
            }
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return $result;
    }

    public function query($query): bool
    {
        $res = false;
        try
        {
            if ($this->sqlCon != null)
            {
                $this->stm = $this->sqlCon->prepare($query);
                if ($this->stm != null)
                {

                    // Bind Named Parameters
                    foreach ($this->namedParam as $key => $value)
                    {
                        $this->stm->bindParam($key, $this->namedParam[$key]);
                    }

                    // Bind Value Parameters
                    foreach ($this->param as $key => $value)
                    {
                        $this->stm->bindValue($key + 1, $value);
                    }

                    $res = $this->stm->execute();
                }


            }
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        $this->clearParam();
        return $res;
    }

    function executeMutliNoneQuery($query)
    {
        $res = false;
        try
        {
            if ($this->sqlCon != null)
            {
                $this->sqlCon->exec($query);
                $this->clearParam();
            }
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }
        return $res;
    }

    public function executeNoneQuery($query): int
    {
        $result = 0;
        $this->lastID_safe = 0;
        if ($this->query($query))
        {
            $result = $this->affectedRows();

            if ($result != null && $result > 0)
            {
                $this->lastID_safe = $this->getLastInsertId();
            }
        }

        return $result;
    }

    public function queryAssoc($query): ?array
    {
        $result = null;

        if ($this->query($query))
        {
            $result = $this->fetchAssoc();
        }
        return $result;
    }

    public function queryObject($query): ?object
    {
        $result = null;

        if ($this->query($query))
        {
            $result = $this->fetchObject();
        }
        return $result;
    }

    public function queryClass($query, $class)
    {
        $result = null;

        if ($this->query($query))
        {
            try
            {
                $result = $this->fetchClass($class) ?? new $class();
            }
            catch (\Exception $ex)
            {

                WriteLog("Mysql Error", $ex, "", "");
            }
        }
        return $result;
    }

    public function queryArray($query)
    {
        $result = null;

        if ($this->query($query))
        {
            $result = $this->fetchArray();
        }
        return $result;
    }

    public function queryScalar($query)
    {
        $result = null;

        if ($this->query($query) && $data = $this->fetchArray())
        {
            $result = $data[0];
        }
        return $result;
    }

    public function queryAllAssoc($query)
    {
        $result = null;

        if ($this->query($query))
        {
            $result = $this->fetchAllAssoc();
        }
        return $result;
    }

    public function queryAllObject($query)
    {
        $result = [];

        if ($this->query($query))
        {
            $result = $this->fetchAllObject();
        }
        return $result ?? [];
    }


    public function queryAllClass($query, $class)
    {
        $result = null;

        if ($this->query($query))
        {
            $result = $this->fetchAllClass($class);
        }
        return $result;
    }

    public function queryAllClass_Safe($query, $class)
    {
        $result = $this->queryAllClass($query, $class);
        return $result;
    }

    public function queryAllArray($query)
    {
        $result = null;

        if ($this->query($query))
        {
            $result = $this->fetchAllArray();
        }
        return $result;
    }

    public function queryAllArray_Safe($query)
    {
        $result = $this->queryAllArray($query);
        return $result;
    }

    public function fetchAssoc()
    {
        try
        {
            if ($this->sqlCon == null)
            {
                throw new Exception();
            }
            return $this->stm->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return null;
    }

    public function fetchObject()
    {
        $result = null;
        try
        {

            $result = $this->stm->fetch(PDO::FETCH_OBJ);

            if ($result === false)
            {
                $result = null;
            }
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return $result;
    }

    public function fetchClass($class): object
    {
        try
        {
            $this->stm->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
            $this->stm->setFetchMode(PDO::FETCH_CLASS, $class);
            return $this->stm->fetch();
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return new $class();
    }

    public function fetchArray()
    {
        try
        {
            return $this->stm->fetch(PDO::FETCH_NUM); //@mysqli_fetch_row($this->stm);
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return null;
    }

    public function fetchAllAssoc()
    {
        try
        {
            return $this->stm->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return null;
    }

    public function fetchAllObject(): array
    {
        try
        {
            return $this->stm->fetchAll(PDO::FETCH_OBJ);
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return [];
    }

    public function fetchAllClass($class): array
    {
        try
        {
            $this->stm->setFetchMode(PDO::FETCH_CLASS, $class);
            return $this->stm->fetchAll();
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return [];
    }

    public function fetchAllArray(): array
    {
        try
        {
            return $this->stm->fetchAll(PDO::FETCH_NUM);
        }
        catch (Exception $ex)
        {
            WriteLog("Mysql Error", $ex, "", "");
        }

        return [];
    }
}
