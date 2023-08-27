<?php

namespace think;

// Cookie管理类
class Cookie
{
    // 配置参数
    protected $config = [
        'expire'    =>  0,      // cookie 保存时间
        'path'      =>  '/',    // cookie 保存路径
        'domain'    =>  '',     // cookie 有效域名
        'secure'    =>  false,  // cookie 启用安全传输
        'httponly'  =>  false,  // httponly设置
        'setcookie' =>  true,   // 是否使用 setcookie
    ];

    /**
     * 构造方法
     * @access public
     * @param  array $config
     */
    public function __construct($config = [])
    {
        $this->init($config);
    }

    /**
     * Cookie初始化
     * @access public
     * @param  array $config
     * @return void
     */
    public function init($config = [])
    {
        $this->config = array_merge($this->config, array_change_key_case($config));
        if ( !empty($this->config['httponly']) && PHP_SESSION_ACTIVE != session_status() ) {
            ini_set('session.cookie_httponly', 1);
        }
    }

    public static function __make(Config $config)
    {
        return new static($config->pull('cookie'));
    }

    /**
     * Cookie 设置
     * @access public
     * @param  string $name  cookie名称
     * @param  string $value cookie值
     * @param  mixed  $option 可选参数 可能会是 integer=3600秒|array=配置数组
     * @return void
     */
    public function set($name, $value = '', $option = null)
    {
        // 参数设置(会覆盖黙认设置)
        if ( !is_null($option) ) {
            if ( is_numeric($option) ) {
                $option = ['expire' => $option];
            }
            $config = array_merge($this->config, array_change_key_case($option));
        } else {
            $config = $this->config;
        }
        $expire = !empty($config['expire']) ? $_SERVER['REQUEST_TIME'] + intval($config['expire']) : 0;
        if ( $config['setcookie'] ) {
            $this->setCookie($name, $value, $expire, $config);
        }
        $_COOKIE[$name] = $value;
    }

    /**
     * Cookie 设置保存
     * @access public
     * @param  string $name  cookie名称
     * @param  mixed  $value cookie值
     * @param  array  $option 可选参数
     * @return void
     */
    protected function setCookie($name, $value, $expire, $option = [])
    {
        setcookie($name, $value, $expire, $option['path'], $option['domain'], $option['secure'], $option['httponly']);
    }

    /**
     * Cookie 永久保存
     * @access public
     * @param  string $name  cookie名称
     * @param  mixed  $value cookie值
     * @param  mixed  $option 可选参数 可能会是 null|integer|string
     * @return void
     */
    public function forever($name, $value = '', $option = null)
    {
        if ( is_null($option) || is_numeric($option) ) {
            $option = [];
        }
        $option['expire'] = 315360000;
        $this->set($name, $value, $option);
    }

    /**
     * 判断Cookie数据
     * @access public
     * @param  string        $name cookie名称
     * @return bool
     */
    public function has($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Cookie获取
     * @access public
     * @param  string   $name cookie名称 留空获取全部
     * @return mixed
     */
    public function get($name = '')
    {
        if ( $name == '' ) {
            $value = $_COOKIE;
        } elseif ( isset($_COOKIE[$name]) ) {
            $value = $_COOKIE[$name];
        } else {
            $value = null;
        }
        return $value;
    }

    /**
     * Cookie删除
     * @access public
     * @param  string        $name cookie名称
     * @return void
     */
    public function delete($name)
    {
        $config = $this->config;
        if ( $config['setcookie'] ) {
            $this->setcookie($name, '', $_SERVER['REQUEST_TIME'] - 3600, $config);
        }
        // 删除指定cookie
        unset($_COOKIE[$name]);
    }

}