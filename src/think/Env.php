<?php

namespace think;

// Env管理类
class Env
{
    /**
     * 环境变量数据
     * @var array
     */
    protected $data = [];

    /**
     * 数据转换映射
     * @var array
     */
    protected $convert = [
        'true'  => true,
        'false' => false,
        'off'   => false,
        'on'    => true,
    ];

    public function __construct()
    {
        $this->data = $_ENV;
    }

    /**
     * 读取环境变量定义文件
     * @access public
     * @param  string    $file  环境变量定义文件
     * @return void
     */
    public function load($file)
    {
        $env = parse_ini_file($file, true, INI_SCANNER_RAW) ?: [];
        $this->set($env);
    }

    /**
     * 获取环境变量值
     * @access public
     * @param  string    $name 环境变量名
     * @param  mixed     $default  默认值
     * @return mixed
     */
    public function get($name = null, $default = null)
    {
        if ( is_null($name) ) {
            return $this->data;
        }
        $name = strtoupper(str_replace('.', '_', $name));
        if ( isset($this->data[$name]) ) {
            $result = $this->data[$name];
            if ( is_string($result) && isset($this->convert[$result]) ) {
                return $this->convert[$result];
            }
            return $result;
        }
        return $this->getEnv($name, $default);
    }

    protected function getEnv($name, $default = null)
    {
        $result = getenv('PHP_' . $name);
        if ( false === $result ) {
            return $default;
        }
        if ( 'false' === $result ) {
            $result = false;
        } elseif ( 'true' === $result ) {
            $result = true;
        }
        if ( !isset($this->data[$name]) ) {
            $this->data[$name] = $result;
        }
        return $result;
    }

    /**
     * 设置环境变量值
     * @access public
     * @param  string|array  $env   环境变量
     * @param  mixed         $value  值
     * @return void
     */
    public function set($env, $value = null)
    {
        if ( is_array($env) ) {
            $env = array_change_key_case($env, CASE_UPPER);
            foreach ( $env as $key => $val ) {
                if ( is_array($val) ) {
                    foreach ( $val as $k => $v ) {
                        $this->data[$key . '_' . strtoupper($k)] = $v;
                    }
                } else {
                    $this->data[$key] = $val;
                }
            }
        } else {
            $name = strtoupper(str_replace('.', '_', $env));
            $this->data[$name] = $value;
        }
    }
    
}