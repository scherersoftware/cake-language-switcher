<?php
declare(strict_types = 1);
namespace LanguageSwitcher;

use Cake\Core\BasePlugin;

class Plugin extends BasePlugin
{
    /**
     * {@inheritdoc}
     */
    public function middleware($middleware)
    {
        return $middleware;
    }
}
