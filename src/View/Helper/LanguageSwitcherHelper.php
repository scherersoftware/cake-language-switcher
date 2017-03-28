<?php
namespace LanguageSwitcher\View\Helper;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Cake\View\Helper;
use Cake\View\View;

/**
 * LanguageSwitcher helper
 */
class LanguageSwitcherHelper extends Helper
{

    use InstanceConfigTrait;

    protected $_defaultConfig = [
        'availableLanguages' => [
            'en_US' => 'en_US'
        ],
        'displayNames' => [
            'en_US' => 'English'
        ],
        'imageMapping' => [
            'en_US' => 'United-States'
        ],
        'renderToggleButtonDisplayName' => true,
        'element' => 'LanguageSwitcher.language_switcher'
    ];

    /**
     * Renders language switcher dropdown
     *
     * @return element
     */
    public function renderLanguageSwitcher()
    {
        return $this->_View->element($this->config('element'), [
            'availableLanguages' => $this->config('availableLanguages'),
            'displayNames' => $this->config('displayNames'),
            'imageMapping' => $this->config('imageMapping'),
            'renderToggleButtonDisplayName' => $this->config('renderToggleButtonDisplayName')
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
        $query = Hash::merge($this->request->query, $lang);
        $urlArray = Hash::merge($this->request->params['pass'], ['?' => $query]);

        return Router::url($urlArray);
    }
}
