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
### composer:
``composer require --dev andersundsehr/grumphp-xliff-task``
