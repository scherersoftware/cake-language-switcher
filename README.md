![CakePHP 3 Language Switcher Plugin](https://raw.githubusercontent.com/scherersoftware/cake-language-switcher/master/language-switcher.png)

[![Build Status](https://travis-ci.org/scherersoftware/cake-language-switcher.svg?branch=master)](https://travis-ci.org/scherersoftware/cake-language-switcher)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require scherersoftware/cake-language-switcher
```

The next step is to load the plugin inside your Application.php:

```
bin/cake plugin load LanguageSwitcher
```

Add the Middleware to your Application.php:

```
$middleware->push(new \LanguageSwitcher\Middleware\LanguageSwitcherMiddleware());
```


Optionally, you can pass an array of options to overwrite the default ones:

```
$middleware->push(new \LanguageSwitcher\Middleware\LanguageSwitcherMiddleware([
    'model' => 'Users',
    'field' => 'language',
    'Cookie' => [
        'name' => 'ChoosenLanguage',
        'expires' => '+1 year',
        'domain' => 'foo.bar'
    ],
    'availableLanguages' => [
        'en_US' => 'en_US'
    ]
]));
```

Add the Helper to your AppView:

```
$this->loadHelper('LanguageSwitcher.LanguageSwitcher');
```

Optionally, you can pass an array of options:

```
$this->loadHelper('LanguageSwitcher.LanguageSwitcher', [
    'availableLanguages' => [
        'en_US' => 'en_US',
        'de_DE' => 'de_DE'
    ],
    'displayNames' => [
        'en_US' => 'English',
        'de_DE' => 'Deutsch'
    ],
    'imageMapping' => [
        'en_US' => 'United-States',
        'de_DE' => 'Germany'
    ],
    'renderToggleButtonDisplayName' => true,
    'element' => 'LanguageSwitcher.language_switcher'
]);
```

To use the element:

```
<?= $this->LanguageSwitcher->renderLanguageSwitcher(); ?>
```

Next, you should migrate your database.

```
bin/cake migrations migrate -p LanguageSwitcher
```

Add the css file located under webroot/css to your layout file!

## Configuration Usage

Inside your app.php add the following to change configs of the plugin:

```
'LanguageSwitcher' => [
    'model' => 'Users',
    'field' => 'language',
    'Cookie' => [
        'name' => 'ChoosenLanguage',
        'expires' => '+1 year'
    ],
    'availableLanguages' => [
        'en_US'
    ],
    'displayNames' => [
        'en_US' => 'English'
    ],
    'imageMapping' => [
        'en_US' => 'United-States'
    ],
    'renderToggleButtonDisplayName' => true,
    'beforeSaveCallback' => function ($user, $request, $response) {
        $language = 'en_EN';
        $user->language = $language;
    }
]
```

- model: The model used in the migration
- field: The field in the model
- Cookie: Optionally you can change the cookie name and the expiration date of the cookie.
- availableLanguages: Add language keys
- displayNames: Should contain the same keys as availableLanguages. Map language key with its Display Name
- imageMapping: Should contain the same keys as availableLanguages. Map language key with its flag image name. (For all possible flag names open webroot/img/flags)
- beforeSaveCallback: Optionally you can override the user entity to set e.g. the language field with a special value
- renderToggleButtonDisplayName: Optionally you can hide the language name in the dropdown toggle button
- element: Optionally you can override the element used for rendering the language switcher with your own.