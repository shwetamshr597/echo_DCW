# Ecommerce121_HideShippingPricesFromCart

This extension removes the sections:
* Estimate shipping and tax
* Gift options
* Gift cards
* Apply discount code

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/HideShippingPricesFromCart/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/HideShippingPricesFromCart/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/HideShippingPricesFromCart/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/HideShippingPricesFromCart/

#### Ticket Reference

[ECHO-97](https://121e.atlassian.net/browse/ECHO-97)
