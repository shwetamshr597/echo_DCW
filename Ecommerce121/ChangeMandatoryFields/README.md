# Ecommerce121_ChangeMandatoryFields

This extension change the telephone as not required for company and customers.

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/ChangeMandatoryFields/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/ChangeMandatoryFields/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/ChangeMandatoryFields/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/ChangeMandatoryFields/

#### Ticket Reference

[ECHO-94](https://121e.atlassian.net/browse/ECHO-94)
