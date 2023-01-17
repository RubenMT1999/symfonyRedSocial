<?php

use App\Kernel;

//composer require symfony/runtime -en la terminal si no reconoce 
//autoload_runtime.
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
