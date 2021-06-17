<h1 align="center">php-slickprogress</h1>

<div align="center">
   <img alt="Latest release" src="https://img.shields.io/github/v/release/aeno/php-slickprogress">
   <img src="https://img.shields.io/badge/License-MPL--2.0-brightgreen" alt="License: MPL-2.0" />
</div>

Lightweight but beautiful PHP progress bars and spinners.

## No dependencies
* works with PHP 7.1+

## Installation

```shell
composer require aeno/php-slickprogress
```

## Usage

### Simple progress
![Simple progress output](docs/slick-simple.gif)
```php
$progress = new \Aeno\SlickProgress\Progress();
$progress->start(50);

for ($i = 0; $i < 50; $i++) {
    $progress->advance();
    usleep(25000);
}

$progress->finish();
```

### Detailed progress
![Detailed progress output](docs/slick-detailed.gif)
```php
$theme = new \Aeno\SlickProgress\Theme\Simple();
$theme->showStep(true);
$theme->showPercent(true);

$progress = new \Aeno\SlickProgress\Progress($theme);
$progress->start(200);

for ($i = 0; $i < 200; $i++) {
    $progress->advance();
    usleep(25000);
}

$progress->finish();
```

### Indefinite progress
![Indefinite progress output](docs/slick-indefinite.gif)
```php
$progress = new \Aeno\SlickProgress\Progress();
$progress->start(-1);

for ($i = 0; $i < 200; $i++) {
    $progress->advance();
    usleep(25000);
}

$progress->finish();
```

### Spinner
![Spinner output](docs/slick-spinner.gif)
```php
$foobar = new \Foobar();    // your business logic

$theme = new \Aeno\SlickProgress\Theme\Snake();
$theme->setColorType(\Aeno\SlickProgress\Colors::COLOR_TYPE_ANSI256);

$progress = new \Aeno\SlickProgress\Progress($theme);
$progress->start(-1);

for ($i = 0; $i < 100; $i++) {
    if ($foobar->hasNewStatus()) {
        $progress->setStatusMessage($foobar->getCurrentStatus());
    }

    $progress->advance();
    usleep(50000);
}

$progress->finish(\Aeno\SlickProgress\ThemeInterface::FINISH_TYPE_MESSAGE, '✅ Done!');
```

## License

php-slickprogress is licensed under the [Mozilla Public License, v. 2.0](https://mozilla.org/MPL/2.0/).
