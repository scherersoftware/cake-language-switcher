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
            'expires' => '+1 year'
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
