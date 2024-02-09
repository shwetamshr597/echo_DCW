# Ecommerce121_EchoIds

This extension add the following fields for these entities and expose for
API calls.

. Company:
- echo_cust_id
- echo_cust_class
- echo_salesRep_id

. Address (Billing and Shipping):

- echo_loc_id

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/EchoIds/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/EchoIds/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/EchoIds/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/EchoIds/

#### Ticket Reference

[ECHO-92](https://121e.atlassian.net/browse/ECHO-92)
