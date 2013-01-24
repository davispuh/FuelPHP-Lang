## Extended Lang class for FuelPHP

This extended Lang class supports localized language URLs

Like
`http://domain/lang1/controller/method`
`http://domain/lang2/controller/method`

Only Lang class is changed and without introducing any side-effects thus making it fully backward compatible and keeping FuelPHP API same, but adding additional functionality.

**Table of Contents**

- [Installation](#installation)
- [Usage](#usage)
- [Authors](#authors)
- [Unlicense](#unlicense)
- [Contribute](#contribute)

## Installation

You can install it with composer or just copy `lang.php` to your `app/classes/`

then in your `app/bootstrap.php` add
```php
Autoloader::add_class('Lang', VENDORPATH . 'davispuh/fuelphp-lang/classes/lang.php');
```

or if you copied it then

```php
Autoloader::add_class('Lang', APPPATH . 'classes/lang.php');
```

## Usage

To use this extended Lang class there are required few code changes, but nothing much.

1. all links which are supposed to be for same language must be replaced with
`Lang::localized($uri)`, eg. `<a href="<?php echo Lang::localized('/blog/article/')?>"/>Article</a>`
(Note: currently `localized` doesn't support schema or relative urls, and it expects that uri starts with `/` but it can be implemented if needed, now can do just `'https://domain'.Lang::localized('/blog/article/')`)
2. make links which change language. either use `Lang::localized('/blog/article/','ru')` or directly write `<a href="/ru">RU</a>`
3. in app `config.php` add available languages `'languages' => Array('en', 'ru')`
4. change `routes.php` config to also include localized routing.

#### `routes.php` config changes explained

Will need routes for both cases when language is included in URI and when isn't.
So will need to add another `_root_` ie. `$routes[$langs]` and all other routes prefixed with `$routes[(({$langs})/)?route]`

There's 4 possible configurations.

##### 1.
SEO friendly. Default language without any prefix. ie. `http://domain/controller` and all other languages `http://domain/lang/controller`. BUT `http://domain/default_lang/controller` returns 404

`config.php`: `'language' => 'en'` // specify which language will be served as default. without included in URL

`routes.php`
```php
<?php

$languages = Config::get('languages');
unset($languages[0]);
$langs = implode('|', $languages);

return array(
'_root_' => 'welcome/index', // The default route
"({$langs})" => 'welcome/index', // The default route in other language
"(({$langs})/)?hello(/:name)?" => array('welcome/hello', 'name' => 'hello'),
);
```


##### 2.
Default language without any prefix. ie. `http://domain/controller`, BUT `http://domain/default_lang/controller` will still work.

everything same as previous, just without `unset($languages[0]);`

##### 3.
All languages `http://domain/lang/controller`. BUT `http://domain/controller` will return 404. There's exception to `_root_`, it will give default language (language_fallback).

`config.php`: `'language' => ''` // no default language, MUST be in URL
`'language_fallback' => 'en'` // Fallback language if language isn't in URL

`routes.php`
```
<?php

$langs = implode('|', Config::get('languages'));

return array(
'_root_' => 'welcome/index', // The default route
"({$langs})" => 'welcome/index', // The default route in other language
"(({$langs})/)?hello(/:name)?" => array('welcome/hello', 'name' => 'hello'),
);
```

##### 4.
Make your own routes, unlimited possibilities. Maybe you don't have some article in that language? No problem, just give in default language or give page informing that it's not available.


## Authors

This extended Lang class is implemented by me @davispuh

Original FuelPHP Lang class is made by Fuel Development Team under MIT license

## Unlicense

All text, documentation, code and files in this repository are in public domain (including this text, README).
It means you can copy, modify, distribute and include in your own work/code, even for commercial purposes, all without asking permission.

## Contribute

Feel free to improve anything what you see is improvable.


**Warning**: By sending pull request to this repository you dedicate any and all copyright interest in pull request (code files and all other) to the public domain. (files will be in public domain even if pull request doesn't get merged)

Also before sending pull request you acknowledge that you own all copyrights or have authorization to dedicate them to public domain.

If you don't want to dedicate code to public domain or if you're not allowed to (eg. you don't own required copyrights) then DON'T send pull request.


