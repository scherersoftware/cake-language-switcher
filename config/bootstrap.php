<?php

use Cake\Core\Configure;
use Cake\Event\EventManager;
use LanguageSwitcher\Middleware\LocalisationMiddleware;

EventManager::instance()->on(
    'Server.buildMiddleware',
    function ($event, $middleware) {
        $middleware->add(new LocalisationMiddleware(Configure::read('LanguageSwitcher')));
    });
