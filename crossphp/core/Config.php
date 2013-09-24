<?php defined('CROSSPHP_PATH')or die('Access Denied');
/**
 * @Auth wonli <wonli@live.com>
 * Class Config 读取app配置
 */
class Config
{
    /**
     * 默认追加的系统配置项
     *
     * @var array
     */
    protected $sys;

    /**
     * app名称
     *
     * @var string
     */
    protected $appname;

    /**
     * 配置资源文件地址
     *
     * @var string
     */
    protected $res_file;

    /**
     * 基础路径
     *
     * @var string
     */
    protected $base_path;

    /**
     * 避免重复加载
     *
     * @var array
     */
    static protected $loaded;

    /**
     * 单例模式
     *
     * @var object
     */
    static protected $instance;

    /**
     * 所有配置
     *
     * @var array
     */
    protected $init;

    function __construct( $appname, $res_file )
    {
        $this->appname = $appname ? $appname : APP_NAME;
        $this->res_file = rtrim(APP_PATH_DIR, DS).DS.$this->appname.DS.$res_file;
    }

    /**
     * 实例化配置类
     *
     * @param $appname
     * @param string $file
     * @return Config
     */
    static function load( $appname = null, $file="init.php" )
    {
        if(! isset(self::$instance [ $appname ]))
        {
            self::$instance [ $appname ] = new Config( $appname, $file );
        }
        return self::$instance [ $appname ];
    }

    /**
     * 解析配置文件和自定义参数
     *
     * @param null $user_config 用户自定义参数
     * @param bool $apped_sys 是否附加系统默认参数
     * @return $this
     */
    function parse($user_config = null, $apped_sys = true)
    {
        $config_data = $this->readConfigFile();

        if(true === $apped_sys)
        {
            $this->sys = $this->getSysSet();

            if(isset($config_data ['sys']))
            {
                $config_data ['sys'] = array_merge($this->sys, array_filter($config_data ['sys']));
            } else {
                $config_data ['sys'] = $this->sys;
            }
        }

        if(null !== $user_config)
        {
            if(!is_array($user_config) && is_file($user_config))
            {
                $configset = require $configset;
                $this->setData($user_config);
                return $this;
            }
            else if(! empty($user_config) && is_array($user_config) )
            {
                foreach($user_config as $key=>$_config)
                {
                    if(is_array($_config)) {
                        foreach($_config as $_config_key=>$_config_value) {
                            if($_config_value) {
                                $config_data [$key] [$_config_key] = $_config_value;
                            }
                        }
                    } else {
                        $config_data [$key] = $_config;
                    }
                }
            }
        }

        $this->setData($config_data);
        return $this;
    }

    /**
     * 保存配置参数
     *
     * @param $init 配置文件
     * @return array
     */
    function setData($init)
    {
        $this->init = $init;
    }

    /**
     * 从文件读取配置文件 支持PHP / JSON
     *
     * @return mixed
     * @throws CoreException
     */
    function readConfigFile()
    {
        $key = crc32($this->res_file);

        if( isset(self::$loaded [$key]) )
        {
            return self::$loaded [$key];
        }

        if(file_exists($this->res_file))
        {
            $ext = Helper::getExt($this->res_file);
            switch($ext)
            {
                case 'php' :
                    $data = require $this->res_file;
                    self::$loaded [$key] = $data;
                    return $data;

                case 'json' :
                    $data = json_decode( file_get_contents($this->res_file), true);
                    self::$loaded [$key] = $data;
                    return $data;

                default :
                    throw new CoreException("不支持的解析格式");
            }
        } else {
            throw new CoreException("配置文件 {$this->res_file} 未找到");
        }
    }

    /**
     * 设置默认追加的系统参数
     *
     * @return array
     */
    private function getSysSet()
    {
        $_sys = array();
        $_sys['host'] = Request::getInstance()->getHostInfo();

        $_sys['base_url'] = Request::getInstance()->getBaseUrl();
        $_sys['site_url'] = $_sys['host'].$_sys['base_url'];

        $_sys['app_name'] = $this->appname;
        $_sys['app_path'] = APP_PATH_DIR.DS.$this->appname;

        $_sys['static_url'] = $_sys["site_url"].'/static/';
        $_sys['static_path'] = Request::getInstance()->getScriptFilePath().DS.'static'.DS;

        $_sys['cache_path'] = $_sys["app_path"].DS.'cache'.DS;

        return $_sys;
    }

    /**
     * 获取配置参数
     * $config为字符串的时候 获取配置数组,此时设定$name 则获取数组中指定项的值
     * $config为数组的时候 获取数组中指定的配置项,如果$name为true 则获取指定项之外的配置项
     *
     * @param $confing 字符串或数组
     * @param $name null或boolean
     * @return string或array
     */
    function get($config, $name=null)
    {
        return CrossArray::init($this->init)->get($config, $name);
    }

    /**
     * 设定配置项的值
     *
     * @param $name 要设定的项
     * @param $values 设定的项的值
     * @return null
     */
    function set($name, $values=null)
    {
        foreach($values as $k=>$v) {
            $this->init[$name][$k] = $v;
        }
    }

    /**
     * 返回全部配置
     *
     * @param   $obj 是否返回对象
     * @return array/object
     */
    function getAll($obj = false)
    {
        return CrossArray::init($this->init)->getAll($obj);
    }
}
