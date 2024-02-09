# Ecommerce121_ButtonsRemoval

This extension remove the "Add" buttons for ButtonsRemoval and customers on the 
backend and the frontend.

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/ButtonsRemoval/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/ButtonsRemoval/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/ButtonsRemoval/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/ButtonsRemoval/

#### Ticket Reference

[ECHO-95](https://121e.atlassian.net/browse/ECHO-95)
