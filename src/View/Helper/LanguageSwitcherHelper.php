<?php
declare(strict_types = 1);
namespace LanguageSwitcher\View\Helper;

use Cake\Core\InstanceConfigTrait;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\View\Helper;

/**
 * LanguageSwitcher helper
 */
class LanguageSwitcherHelper extends Helper
{
    use InstanceConfigTrait;

    protected $_defaultConfig = [
        'availableLanguages' => [
            'en_US' => 'en_US',
        ],
        'displayNames' => [
            'en_US' => 'English',
        ],
        'imageMapping' => [
            'en_US' => 'United-States',
        ],
        'renderToggleButtonDisplayName' => true,
        'element' => 'LanguageSwitcher.language_switcher',
    ];

    /**
     * Renders language switcher dropdown
     *
     * @return string
     */
    public function renderLanguageSwitcher()
    {
        return $this->_View->element($this->getConfig('element'), [
            'availableLanguages' => $this->getConfig('availableLanguages'),
            'displayNames' => $this->getConfig('displayNames'),
            'imageMapping' => $this->getConfig('imageMapping'),
            'renderToggleButtonDisplayName' => $this->getConfig('renderToggleButtonDisplayName'),
        ]);
    }

    /**
     * Merge current GET parameters with the language string
     *
     * @param string $language Language
     * @return string Url
     */
    public function getUrl($language)
    {
        $lang = ['lang' => $language];
        $query = Hash::merge($this->getView()->getRequest()->getQueryParams(), $lang);
        $urlArray = Hash::merge($this->getView()->getRequest()->getParam('pass'), ['?' => $query]);

        return Router::url($urlArray);
    }
}
