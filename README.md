![CakePHP 3 Language Switcher Plugin](https://raw.githubusercontent.com/scherersoftware/cake-language-switcher/master/language-switcher.png)

[![Build Status](https://travis-ci.org/scherersoftware/cakephp-app-template.svg?branch=master)](https://travis-ci.org/scherersoftware/cake-language-switcher)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require scherersoftware/cake-language-switcher
```

The next step is to load the plugin inside your bootstrap.php:

```
bin/cake plugin load LanguageSwitcher
```

Add the plugin to your AppView:

```
$this->loadHelper('LanguageSwitcher.LanguageSwitcher');
```

Optionally you can pass as second parameter your changed configurations:

```
$this->loadHelper('LanguageSwitcher.LanguageSwitcher', Configure::read('LanguageSwitcher'));
```

And use the element:

```
<?= $this->LanguageSwitcher->renderLanguageSwitcher(); ?>
```

Next, you should migrate your database.

```
bin/cake migrations migrate -p LanguageSwitcher
```

## Configuration Usage

Inside your app.php you can add additional and change configs of the plugin.

Default config:

```
LanguageSwitcher => [
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
    ]
]
```

- model: The model used in the migration
- field: The field in the model
- Cookie: Optionally you can change the cookie name and the expiration date of the cookie.
- availableLanguages: Add language keys
- displayNames: Should contain the same keys as availableLanguages. Map language key with its Display Name 
- imageMapping: Should contain the same keys as availableLanguages. Map language key with its flag image name. (For all possible flag names open webroot/img/flags)
