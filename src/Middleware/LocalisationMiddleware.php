<?php
namespace LanguageSwitcher\Middleware;

use App\Lib\Environment;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;
use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \RuntimeException;

class LocalisationMiddleware
{

    use InstanceConfigTrait;

    protected $_defaultConfig = [
        'model' => 'Users',
        'field' => 'language',
        'Cookie' => [
            'name' => 'ChoosenLanguage',
            'expires' => '+1 year',
            'domain' => ''
        ],
        'availableLanguages' => [
            'en_US'
        ]
    ];

    /**
     * Constructor.
     *
     * @param array $config config
     */
    public function __construct($config = [])
    {
        $this->config($config);
        if (empty($this->config('Cookie.domain'))) {
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
        if (isset($request->getCookieParams()[$this->getCookieName()])) {
            $cookieLocale = $request->getCookieParams()[$this->getCookieName()];
        }

        $queryLocale = $this->getQueryLocale($request);
        $session = $request->getAttribute('session');
        $user = $session->read('Auth.User');

        if (isset($user)) {
            $usersTable = TableRegistry::get($this->config('model'));
            $user = $usersTable->get($user['id']);

            if (!isset($user->{$this->config('field')})) {
                $user->{$this->config('field')} = $cookieLocale;
                $usersTable->save($user);
            }

            if (isset($queryLocale) && in_array($queryLocale, $this->getAllowedLanguages())) {
                $user->{$this->config('field')} = $queryLocale;
                $usersTable->save($user);
            }

            $this->setCookieAndLocale($user->{$this->config('field')});
            return $next($request, $response);
        }

        if (isset($queryLocale)) {
            $this->setCookieAndLocale($queryLocale);
            return $next($request, $response);
        }

        if (!isset($queryLocale) && isset($cookieLocale)) {
            I18n::locale($cookieLocale);
            return $next($request, $response);
        }

        $locale = Locale::acceptFromHttp($request->getHeaderLine('Accept-Language'));
        if (!$locale) {
            return $next($request, $response);
        }

        if (in_array($locale, $this->getAllowedLanguages()) || $this->getAllowedLanguages() === ['*']) {
            $this->setCookieAndLocale($locale);
        }

        return $next($request, $response);
    }

    /**
     * Get Query Locale
     * @param  ServerRequestInterface $request  The request.
     * @return string       locale string
     */
    private function getQueryLocale($request)
    {
        if (isset($request->getQueryParams()['lang'])) {
            return $request->getQueryParams()['lang'];
        }
    }

    /**
     * Set the cookie and the locale
     *
     * @param string $locale locale
     */
    private function setCookieAndLocale($locale)
    {
        $time = $this->getCookieExpireTime();
        if (in_array($locale, $this->getAllowedLanguages())) {
            I18n::locale($locale);
            setcookie($this->getCookieName(), $locale, $time, '/', $this->config('Cookie.domain'));
        }
    }

    /**
     * Get all allowed browser languages
     *
     * @return array
     */
    private function getAllowedLanguages()
    {
        return $this->config('availableLanguages');
    }

    /**
     * Get the cookie name
     *
     * @return string
     */
    private function getCookieName()
    {
        return $this->config('Cookie.name');
    }

    /**
     * Get the cookie expiration date
     *
     * @return int
     */
    private function getCookieExpireTime()
    {
        $time = new Time($this->config('Cookie.expires'));
        return $time->toUnixString();
    }
}
