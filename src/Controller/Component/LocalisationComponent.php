<?php
namespace LanguageSwitcher\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\I18n\I18n;

/**
 * LanguageSwitcher component
 */
class LocalisationComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'model' => 'Users',
        'field' => 'language',
        'Cookie' => [
            'name' => 'choosen_language',
            'cookie_lifetime' => (365 * 24 * 60 * 60)
        ]
    ];

    /**
     * Set locale
     *
     * @return void
     */
    public function setLocale()
    {
    }
}
