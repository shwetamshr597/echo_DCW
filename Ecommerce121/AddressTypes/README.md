# Ecommerce121_AddressTypes

This extension adds a field 'address_type' to the "customer_address_entity" table.
This field will contain 1 of 2 possible values: 'Shipping', or 'Billing'. This value will come from the future integration to the ERP.

Depending on the value of this field, the customer should be shown only the addresses that are 'address_type' == 'shipping' on the shipping step of the checkout, while only showing the 'Billing' addresses on the billing step.

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/AddressTypes/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/AddressTypes/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/AddressTypes/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/AddressTypes/

#### CHANGE LOG:
 <table>
  <tr>
    <th>Date</th>
    <th>Issue</th>
    <th>Brief note</th>
  </tr>
  <tr>
    <td>2023-06-12</td>
    <td><a href="https://jira.121ecommerce.co/browse/ECHO-96">ECHO-96</a></td>
    <td>Initial installation of extension.</td>
  </tr>
  <tr>
    <td>2023-06-12</td>
    <td><a href="https://121e.atlassian.net/browse/ECHO-117">ECHO-117</a></td>
    <td>Remove "New Address" option in billing step checkout.</td>
  </tr>
</table> 
