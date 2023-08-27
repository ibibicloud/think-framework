<?php

namespace think\validate;

// 验证规则
class ValidateRule
{
    // 验证字段的名称
    protected $title;

    // 当前验证规则
    protected $rule = [];

    // 验证提示信息
    protected $message = [];

    /**
     * 添加验证因子
     * @access protected
     * @param  string    $name  验证名称
     * @param  mixed     $rule  验证规则
     * @param  string    $msg   提示信息
     * @return $this
     */
    protected function addItem($name, $rule = null, $msg = '')
    {
        if ( $rule || 0 === $rule ) {
            $this->rule[$name] = $rule;
        } else {
            $this->rule[] = $name;
        }
        $this->message[] = $msg;
        return $this;
    }

    /**
     * 获取验证规则
     * @access public
     * @return array
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * 获取验证字段名称
     * @access public
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 获取验证提示
     * @access public
     * @return array
     */
    public function getMsg()
    {
        return $this->message;
    }

    /**
     * 设置验证字段名称
     * @access public
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function __call($method, $args)
    {
        if ( 'is' == strtolower(substr($method, 0, 2)) ) {
            $method = substr($method, 2);
        }
        array_unshift($args, lcfirst($method));
        return call_user_func_array([$this, 'addItem'], $args);
    }

    public static function __callStatic($method, $args)
    {
        $rule = new static();
        if ( 'is' == strtolower(substr($method, 0, 2)) ) {
            $method = substr($method, 2);
        }
        array_unshift($args, lcfirst($method));
        return call_user_func_array([$rule, 'addItem'], $args);
    }

}