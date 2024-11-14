<?php

class SiteRoute
{

    public array $pathParts;
    public string $pathRoute;
    public bool $valid;
    public bool $hasPath;
    public int $routeCount;
    public string $pageName;
    public string $pathRoot;
    public bool $HasFile;
    public string $FileName;
    public bool $IsUrlQuery;
    public bool $HasRouteRequest;
    public string $RouteRequest;
    public string $RoutePage;
    public array $RouteArguments;
    public string $fullRoute;

    private string $viewPath;

    private object $viewEngine;
    private array $context;

    /**
     * Get the value of Path Parts
     */
    public function getPathParts(): array
    {
        return $this->pathParts;
    }

    /**
     * Set the value of Path Parts
     * @param array $pathParts
     * @return SiteRoute
     */
    public function setPathParts(array $pathParts): self
    {
        $this->pathParts = $pathParts;
        return $this;
    }

    public function __construct($viewEngine = null, $viewPath = 'application/view/')
    {
        $this->pathParts = array();
        $this->pathRoute = "";
        $this->valid = true;
        $this->hasPath = false;
        $this->routeCount = 0;
        $this->pageName = "home";
        $this->pathRoot = "";
        $this->HasFile = false;
        $this->FileName = "";
        $this->IsUrlQuery = false;
        $this->HasRouteRequest = false;
        $this->RouteRequest = "";
        $this->RoutePage = "";
        $this->RouteArguments = [];
        $this->viewEngine = $viewEngine;
        $this->viewPath = $viewPath;
        $this->context = [];
    }

    /**
     * Render our current our current route path
     * @param array $context
     * @param string $template
     * @return void
     */
    public function renderView(array $context = [], string $template = '')
    {
        if (!isset($this->viewEngine))
        {
            return;
        }

        $viewFile = $fileRoute = sprintf("%s.twig", $this->fullRoute);

        if (!empty($template))
        {
            $viewFile = $fileRoute = sprintf("%s.twig", $template);
        }

        foreach ($context as $key => $value)
        {
            $this->addContext($key, $value);
        }

        if (file_exists($this->viewPath . $viewFile))
        {
            echo $this->viewEngine->render($fileRoute, $this->context);
        }
    }

    /**
     * Add a context to our view
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addContext($key, $value)
    {
        $this->context[$key] = $value;
    }

    /**
     * Redirect to a given url
     * @param string $url
     * @return void
     */
    public function redirect($url)
    {
        header("location: $url");
        exit();
    }

    /**
     * Get the value of View Path
     */
    public static function getSitePath($value, $DefaultPage, $removeBase = 0, $viewEngine = null, $viewPath = 'application/view/'): self
    {

        $val = urldecode($value);

        $len = strlen($val);
        if ($removeBase > 0 && $removeBase <= $len)
        {
            $max = $len - $removeBase;
            $val = substr($val, $removeBase, $max);
        }

        $siteRouteData = new self($viewEngine, $viewPath);
        $siteRouteData->pathParts = array();
        $siteRouteData->pathRoute = "";
        $siteRouteData->valid = true;
        $siteRouteData->hasPath = false;
        $siteRouteData->routeCount = 0;
        $siteRouteData->pageName = $DefaultPage;
        $siteRouteData->pathRoot = "";
        $siteRouteData->HasFile = false;
        $siteRouteData->FileName = "";

        $siteRouteData->IsUrlQuery = false;
        $siteRouteData->HasRouteRequest = false;
        $siteRouteData->RouteRequest = "";
        $siteRouteData->RoutePage = "";
        $siteRouteData->RouteArguments = [];

        // Contains arguments?
        $pos = stripos($val, '?');
        if ($pos !== false)
        {
            $sample = explode('?', $val);
            if ($pos > 0 && count($sample) > 1)
            {
                $val = trim($sample[0]);
            }
        }

        // New 
        $pos = strpos($val, "/#/");
        $posurl = strpos($val, "/$");
        $posurl1 = strpos($val, "/$/");
        if ($pos !== false && strlen($val) >= $pos + 3)
        {
            $siteRouteData->RouteRequest = trim(trim(substr($val, $pos + 3), "/"));
            $tempArr = explode("/", $siteRouteData->RouteRequest);

            $siteRouteData->RoutePage = $tempArr[0];

            if (count($tempArr) > 1)
            {
                unset($tempArr[0]);
                $siteRouteData->RouteArguments = array_values($tempArr);
            }

            $siteRouteData->HasRouteRequest = true;
            $val = substr($val, 0, $pos);
        }
        else if ($posurl1 !== false && strlen($val) > $posurl1 + 2)
        {
            $siteRouteData->RouteRequest = trim(trim(substr($val, $posurl1 + 2), "/"));
            $tempArr = explode("/", $siteRouteData->RouteRequest);
            $siteRouteData->RouteArguments = $tempArr;
            $siteRouteData->IsUrlQuery = true;
            $val = substr($val, 0, $posurl1);
        }
        else if ($posurl !== false && strlen($val) > $posurl + 1)
        {
            $siteRouteData->RouteRequest = trim(trim(substr($val, $posurl + 2), "/"));
            $siteRouteData->IsUrlQuery = true;
            $val = substr($val, 0, $posurl);
        }

        $valueK = trim(trim($val, "/"));

        $siteRouteData->pathParts = explode("/", $valueK);
        $siteRouteData->pathRoute = $valueK;

        // Check if the entered path is valid or not
        $siteRouteData->valid = SiteRoute::ValidatePathPart($siteRouteData->pathParts);

        $siteRouteData->hasPath = $siteRouteData->pathRoute != "";
        $siteRouteData->routeCount = count($siteRouteData->pathParts);

        if ($siteRouteData->routeCount > 0)
        {
            $c = $siteRouteData->routeCount - 1;
            $siteRouteData->pathRoot = "";

            for ($i = 0; $i < $c; $i++)
            {
                $siteRouteData->pathRoot .= $siteRouteData->pathParts[$i] . "/";
            }

            $tempPage = trim($siteRouteData->pathParts[$siteRouteData->routeCount - 1]);

            $siteRouteData->fullRoute = $tempPage == "" ? $siteRouteData->pathRoute . "/home" : $siteRouteData->pathRoute;
            $siteRouteData->pageName = $tempPage == "" ? "home" : $tempPage;
        }
        else
        {

        }


        return $siteRouteData;
    }

    /**
     * Validate the path part
     * @param array $value
     * @return bool
     */
    public static function ValidatePathPart($value)
    {

        $co = count($value);
        if ($co > 0)
        {
            for ($i = 0; $i < $co; $i++)
            {
                if (!ValidationClass::ValidateAlphaNumeric($value[$i]))
                {
                    return false;
                }
            }
        }

        return $co > 0; // true;
    }

    /**
     * Build the path
     * @param array $siteRoute
     * @param int $len
     * @return array
     */
    public static function BuildPathMod($siteRoute, $len = 0)
    {
        $scriptPaths = array();
        $count = count($siteRoute) - $len;
        $last = "";
        $scriptPaths[] = $last; // . '.php'; // Push Main path

        if ($count > 0)
        {
            for ($i = 0; $i < $count; $i++)
            {
                $last .= $siteRoute[$i] . '/';
                $scriptPaths[] = $last; // . '.php';
            }
        }

        return $scriptPaths;
    }

    public static function MapFromRoute($siteRoute, $paramArray)
    {
        $result = new stdClass();
        if (is_array($paramArray) && count($paramArray) > 0)
        {
            $paramCount = count($paramArray);
            $arrCount = count($siteRoute->RouteArguments);

            for ($i = 0; $i < $paramCount; $i++)
            {
                $varParam = $paramArray[$i];
                $result->$varParam = $i < $arrCount ? $siteRoute->RouteArguments[$i] : "";
            }
        }

        return $result;
    }

    public static function MapFromJson($inputJson, $paramArray)
    {
        $result = new stdClass();
        if ($inputJson != null && is_array($paramArray) && count($paramArray) > 0)
        {
            $paramCount = count($paramArray);

            for ($i = 0; $i < $paramCount; $i++)
            {
                $varParam = $paramArray[$i];
                $result->$varParam = isset($inputJson->$varParam) ? $inputJson->$varParam : "";
            }
        }
        else
        {
            $paramCount = count($paramArray);
            for ($i = 0; $i < $paramCount; $i++)
            {
                $varParam = $paramArray[$i];
                $result->$varParam = "";
            }
        }

        return $result;
    }

    public static function MapFromGet($paramArray, $stripTags = false)
    {
        $result = new stdClass();
        if (is_array($paramArray) && count($paramArray) > 0)
        {
            $paramCount = count($paramArray);
            for ($i = 0; $i < $paramCount; $i++)
            {
                $varParam = $paramArray[$i];
                $result->$varParam = isset($_GET[$varParam]) ?
                    $stripTags ? strip_tags($_GET[$varParam]) : $_GET[$varParam] : "";
            }
        }
        else
        {
            $paramCount = count($paramArray);
            for ($i = 0; $i < $paramCount; $i++)
            {
                $varParam = $paramArray[$i];
                $result->$varParam = "";
            }
        }

        return $result;
    }

    public static function MapFromPost($paramArray, $stripTags = false)
    {
        $result = new stdClass();
        if (is_array($paramArray) && count($paramArray) > 0)
        {
            $paramCount = count($paramArray);
            for ($i = 0; $i < $paramCount; $i++)
            {
                $varParam = $paramArray[$i];
                $result->$varParam = isset($_POST[$varParam]) ?
                    $stripTags ? strip_tags($_POST[$varParam]) : $_POST[$varParam] : "";
            }
        }
        else
        {
            $paramCount = count($paramArray);
            for ($i = 0; $i < $paramCount; $i++)
            {
                $varParam = $paramArray[$i];
                $result->$varParam = "";
            }
        }

        return $result;
    }
}
