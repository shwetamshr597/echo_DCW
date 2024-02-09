# Ecommerce121_NonEditableCustomerAddress

This extension remove the possibility to add or edit addresses for customer
on the frontend and the backend. All the addresses are set from the ERP, if for
some reason a customer goes to the checkout without an address it will be 
redirected to the contact page with a message.

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/NonEditableCustomerAddress/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/NonEditableCustomerAddress/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/NonEditableCustomerAddress/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/NonEditableCustomerAddress/

#### Ticket Reference

[ECHO-91](https://121e.atlassian.net/browse/ECHO-91)
