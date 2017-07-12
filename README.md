[![Packagist](https://img.shields.io/packagist/v/pluswerk/grumphp-xliff-task.svg?style=flat-square)](https://packagist.org/packages/pluswerk/grumphp-xliff-task)
[![Packagist](https://img.shields.io/packagist/l/pluswerk/grumphp-xliff-task.svg?style=flat-square)](https://opensource.org/licenses/LGPL-3.0)
[![Travis](https://img.shields.io/travis/Kanti/LJSON.svg?style=flat-square)](https://travis-ci.org/Pluswerk/grumphp-xliff-task)
[![Code Climate](https://img.shields.io/codeclimate/github/pluswerk/grumphp-xliff-task.svg?style=flat-square)](https://codeclimate.com/github/pluswerk/grumphp-xliff-task)
# grumphp-xliff-task
GrumPHP task to lint xlf/xliff Files
### grumphp.yml:
````yml
parameters:
    tasks:
        xlifflint:
            ignore_patterns: []
            load_from_net: false
            x_include: false
            dtd_validation: false
            scheme_validation: false
            triggered_by: [xlf]
    extensions:
        - PLUS\GrumPHPXliffTask\ExtensionLoader
````
### upgrade from andersundsehr/gurmphp-xliff-task
If you come from [andersundsehr/grumphp-xliff-config](https://hitgub.com/andersundsehr/grumphp-xliff-config), change the extensions Loader path in the grumphp.yml file. 
````yml
        - AUS\GrumPHPXliffTask\ExtensionLoader
````
from:
to:
````yml
        - PLUS\GrumPHPXliffTask\ExtensionLoader
````

### composer:
``composer require --dev pluswerk/grumphp-xliff-task``
