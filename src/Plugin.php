<?php
declare(strict_types = 1);
namespace LanguageSwitcher;

use Cake\Core\BasePlugin;
use Cake\Http\MiddlewareQueue;

class Plugin extends BasePlugin
{
    /**
     * {@inheritdoc}
     */
    public function middleware(MiddlewareQueue $middleware): MiddlewareQueue
    {
        return $middleware;
    }
}
