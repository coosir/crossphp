<?php
/**
 * @Auth: wonli <wonli@live.com>
 * Rest.php
 */

class Rest
{
    /**
     * @var object
     */
    static $instance;

    /**
     * @var object
     */
    protected $config;

    /**
     * @var object
     */
    protected $request;

    /**
     * @var string
     */
    protected $request_string;

    /**
     * 初始化request
     */
    private function __construct( $config )
    {
        $this->request = Request::getInstance();
        $this->config = $config;

        $url_type = $this->config->get('url', 'type');
        $this->request_string = $this->request->getUrlRequest( $url_type );
    }

    /**
     * 创建rest实例
     *
     * @param $config
     * @return Rest
     */
    static function getInstance( $config )
    {
        if(! self::$instance)
        {
            self::$instance = new Rest( $config );
        }

        return self::$instance;
    }

    /**
     * GET
     *
     * @param $request_url
     * @param callable $process_func
     */
    function get( $request_url, Closure $process_func )
    {
        if( true !== $this->request->isGetRequest() )
        {
            return;
        }

        $params = array();
        if( true === $this->checkRequest( $request_url, $params ))
        {
            $this->response( call_user_func_array($process_func, $params) );
        }
    }

    /**
     * POST
     *
     * @param $request_url
     * @param callable $process_func
     */
    function post( $request_url, Closure $process_func )
    {
        if( true !== $this->request->isPostRequest() )
        {
            return;
        }

        if(strcasecmp($request_url, $this->request_string) !== 0)
        {
            return;
        }

        $data = file_get_contents("php://input");
        $this->response( call_user_func($process_func, $data) );
    }

    /**
     * PUT
     *
     * @param $request_url
     * @param callable $process_func
     */
    function put( $request_url, Closure $process_func )
    {
        if( true !== $this->request->isPutRequest() )
        {
            return;
        }

        if(strcasecmp($request_url, $this->request_string) !== 0)
        {
            return;
        }

        $data = file_get_contents("php://input");
        $this->response( call_user_func($process_func, $data) );
    }

    /**
     * PUT
     *
     * @param $request_url
     * @param callable $process_func
     */
    function delete( $request_url, Closure $process_func )
    {
        if( true !== $this->request->isDeleteRequest() )
        {
            return;
        }

        $params = array();
        if( true === $this->checkRequest( $request_url, $params ))
        {
            $this->response( call_user_func_array($process_func, $params) );
        }
    }

    /**
     * 检查参数是否与请求字符串对应
     *
     * @param $request_url
     * @param $params
     * @return bool
     */
    function checkRequest( $request_url, & $params )
    {
        $url_dot = $this->config->get("url", "dot");
        $params_key = array();
        if(false !== strpos($request_url, "{$url_dot}:"))
        {
            $params_start = strpos( $request_url, "{$url_dot}:" );
            $params_key = array_filter( explode("{$url_dot}:", substr( $request_url, $params_start )));
            $request_url = substr( $request_url, 0, $params_start );
        }

        if(0 === strncasecmp($this->request_string, $request_url, strlen($request_url)))
        {
            if(! empty($params_key))
            {
                $params_value = array_filter( explode("{$url_dot}", substr($this->request_string, strlen($request_url))));
            }
        }
        else
        {
            return false;
        }

        if( isset($params_value) && count($params_key) == count($params_value) )
        {
            $params = array_combine($params_key, $params_value);
        }
        else
        {
            return false;
        }

        return true;
    }

    /**
     * 返回结果
     *
     * @param $rep
     */
    function response( $rep )
    {
        Response::getInstance()->output(200, $rep);
    }
}