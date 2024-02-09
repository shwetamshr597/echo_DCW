# Ecommerce121_OrderFields

This extension adds a new field in tables sales_order and sales_order_grid called
echo_ord_id also include this in extension attributes so that way we pass the value
over the API when we get an order.

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/OrderFields/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/OrderFields/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/OrderFields/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/OrderFields/

#### Ticket Reference

[ECHO-93](https://121e.atlassian.net/browse/ECHO-93)
