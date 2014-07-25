<?php
/**
 * @Auth: wonli <wonli@live.com>
 * Class RequestRedisCache
 */
namespace cross\cache;

use cross\i\CacheInterface;

class RequestRedisCache extends RedisCache implements CacheInterface
{
    function __construct($option)
    {
        parent::__construct($option);
        $this->cache_key = $option ['key'];
        $this->key_ttl = $option ['expire_time'];
    }

    /**
     * 设置request请求
     *
     * @param $key
     * @param $value
     * @return mixed|void
     */
    function set($key, $value)
    {
        $this->link->setex($this->cache_key, $this->key_ttl, $value);
    }

    /**
     * 检查缓存key是否有效
     *
     * @return bool
     */
    function getExtime()
    {
        return $this->link->ttl($this->cache_key) > 0;
    }

    /**
     * 返回request的内容
     *
     * @param string $key
     * @return bool|mixed|string
     */
    function get($key = '')
    {
        if (!$key) {
            $key = $this->cache_key;
        }

        return $this->link->get($key);
    }
}
