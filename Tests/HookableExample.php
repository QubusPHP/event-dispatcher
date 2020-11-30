<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

class HookableExample
{
    /**
     * Class instance.
     */
    private static $singleton;

    public static function getInstance()
    {
        return self::$singleton = new self;
    }

    public static function singletonMethod()
    {
        return self::getInstance();
    }

    public function meta($title)
    {
        return 'meta-hook';
    }

    public function css()
    {
        return 'css-hook';
    }

    public function js()
    {
        return 'js-hook';
    }

    public function afterBody()
    {
        return 'after-hook';
    }

    public function form($input, $select)
    {
        return 'form-hook';
    }

    public function article()
    {
        return 'article-hook';
    }

    public function footer()
    {
        return 'footer-hook';
    }
}
