<?php
namespace LanguageSwitcher\Middleware;

use App\Lib\Environment;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Throwable;

class LanguageSwitcherMiddleware
{

    use InstanceConfigTrait;

    protected $_defaultConfig = [
        'model' => 'Users',
        'field' => 'language',
        'Cookie' => [
            'name' => 'ChoosenLanguage',
            'expires' => '+1 year',
            'domain' => '',
            'canonicalizeLocale' => true
        ],
        'availableLanguages' => [
            'en_US' => 'en_US'
        ],
        'beforeSaveCallback' => null,
        'additionalConfigFiles' => []
    ];

    /**
     * Constructor.
     *
     * @param array $config config
     */
    public function __construct($config = [])
    {
        $this->setConfig($config);
        if (empty($this->getConfig('Cookie.domain'))) {
            throw new RuntimeException('Missing config Cookie.domain for ' . get_class($this));
        }
    }

    /**
     * Sets the locale to user locale or browser locale
     *
     * @param ServerRequestInterface $request  The request.
     * @param ResponseInterface $response The response.
     * @param callable $next The next middleware to call.
     *
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $cookieLocale = null;
        if (isset($request->getCookieParams()[$this->__getCookieName()])) {
            $cookieLocale = $request->getCookieParams()[$this->__getCookieName()];
        }

        $queryLocale = $this->__getQueryLocale($request);
        $session = $request->getAttribute('session');
        $user = $session->read('Auth.User');

        if (isset($user)) {
            $usersTable = TableRegistry::getTableLocator()->get($this->getConfig('model'));

            try {
                $user = $usersTable->get($user['id']);
            } catch (Throwable $t) {
                // Return early if the user is not found.
                return $this->__next($request, $response, $next);
            }

            $beforeSaveCallback = $this->getConfig('beforeSaveCallback');
            if (isset($beforeSaveCallback)
                && is_callable($beforeSaveCallback)
            ) {
                $beforeSaveCallback($user, $request, $response);
            }

            if (!isset($user->{$this->getConfig('field')})) {
                $user->{$this->getConfig('field')} = $cookieLocale;
                $usersTable->save($user);
            }

            if (isset($queryLocale) && in_array($queryLocale, $this->__getAllowedLanguages())) {
                if ($user->{$this->getConfig('field')} !== $queryLocale) {
                    $user->{$this->getConfig('field')} = $queryLocale;
                    $usersTable->save($user);
                }
            }

            $this->__setCookieAndLocale($user->{$this->getConfig('field')});

            return $this->__next($request, $response, $next);
        }

        if (isset($queryLocale)) {
            $this->__setCookieAndLocale($queryLocale);

            return $this->__next($request, $response, $next);
        }

        if (!isset($queryLocale) && isset($cookieLocale)) {
            I18n::setLocale($cookieLocale);

            return $this->__next($request, $response, $next);
        }

        $locale = Locale::acceptFromHttp($request->getHeaderLine('Accept-Language'));
        if (!$locale) {
            return $this->__next($request, $response, $next);
        }
        if ($this->__getAllowedLanguages() !== ['*']) {
            $locale = Locale::lookup($this->__getAllowedLanguages(), $locale, $this->getConfig('Cookie.canonicalizeLocale'), Configure::read('App.defaultLocale'));
            if ($locale === '') {
                $locale = Configure::read('App.defaultLocale');
            }
        }
        if ($locale || $this->__getAllowedLanguages() === ['*']) {
            $this->__setCookieAndLocale($locale);
        }

        return $this->__next($request, $response, $next);
    }

    /**
     * Calls the next middleware.
     *
     * @param ServerRequestInterface $request  The request.
     * @param ResponseInterface $response The response.
     * @param callable $next The next middleware to call.
     *
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    private function __next(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $this->__loadConfigFiles();

        return $next($request, $response);
    }

    /**
     * Get Query Locale
     * @param  ServerRequestInterface $request  The request.
     * @return string       locale string
     */
    private function __getQueryLocale($request)
    {
        if (isset($request->getQueryParams()['lang'])) {
            return $request->getQueryParams()['lang'];
        }
    }

    /**
     * Set the cookie and the locale
     *
     * @param string $locale locale
     * @return void
     */
    private function __setCookieAndLocale($locale)
    {
        // @FIXME Should be refactored when cake 3.4 was released
        if (PHP_SAPI !== 'cli') {
            $time = $this->__getCookieExpireTime();
            I18n::setLocale($locale);
            setcookie($this->__getCookieName(), $locale, $time, '/', $this->getConfig('Cookie.domain'));
        }
    }

    /**
     * Loads additional config files that require the language to be set correctly.
     *
     * @return void
     */
    private function __loadConfigFiles()
    {
        $additionalConfigs = $this->getConfig('additionalConfigFiles');
        foreach ($additionalConfigs as $additionalConfig) {
            Configure::load($additionalConfig);
        }
    }

    /**
     * Get all allowed browser languages
     *
     * @return array
     */
    private function __getAllowedLanguages()
    {
        return $this->getConfig('availableLanguages');
    }

    /**
     * Get the cookie name
     *
     * @return string
     */
    private function __getCookieName()
    {
        return $this->getConfig('Cookie.name');
    }

    /**
     * Get the cookie expiration date
     *
     * @return int
     */
    private function __getCookieExpireTime()
    {
        $time = new Time($this->getConfig('Cookie.expires'));

        return $time->toUnixString();
    }
}
