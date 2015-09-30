<<<<<<< HEAD
yii2-imagehelper
===============

Add Yii2-user to the require section of your composer.json file:
```json
{
    "require": {
        "sblazheev/yii2-imagehelper": "*"
    }
}
```

Run
```php
  php composer.phar update
```

Setup config
```php
'components' => [
        ...
        'ImageHelper' => [
            'class' => 'sblazheev\yii2imagehelper\ImageHelper',
            'cachePath' => '@app/web/images/cache',
            'cacheUrl' => '/images/cache',
        ],
  ]
```


#How to use
```php

```
=======
# yii2-imagehelper
>>>>>>> 2227e456dd7aacf31c134a0dab1568cf48092fd1
