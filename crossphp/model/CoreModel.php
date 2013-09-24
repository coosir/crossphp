<?php defined('CROSSPHP_PATH')or die('Access Denied');
/**
 * @Author: wonli <wonli@live.com>
 */
class CoreModel
{
    static function factory($link_type, $link_params)
    {
        switch( strtolower($link_type) )
        {
            case 'mysql' :

                $host = $link_params['host'];
                $name = $link_params['name'];
                $port = isset($link_params['port'])?$link_params['port']:3306;
                $char_set = isset($link_params['chatset'])?$link_params['chatset']:'utf8';

                $dsn = "mysql:host={$host};dbname={$name};port={$port};charset={$char_set}";
                return MysqlModel::getInstance($dsn, $link_params["user"], $link_params["pass"]);

            case 'mongodb':
                return true;

            case 'redis':
                return new RedisCache($link_params);

            default:
                throw new CoreException("不支持的数据库类型!");
        }
    }
}
