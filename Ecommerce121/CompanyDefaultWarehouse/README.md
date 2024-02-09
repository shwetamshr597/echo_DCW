# Ecommerce121_CompanyDefaultWarehouse

This extension adds the field 'default_warehouse_id' to the Company entity.
The value of the field will correspond to the source code of a Magento inventory source.
A customer that is part of a company that has a value set for 'default_warehouse_id' will see the available stock of any given item that pertains only to that inventory source.
When an order gets placed, the same 'default_company_id' value that is in the customer's company will be saved into the order.

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/CompanyDefaultWarehouse/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/CompanyDefaultWarehouse/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/CompanyDefaultWarehouse/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/CompanyDefaultWarehouse/

#### CHANGE LOG:
 <table>
  <tr>
    <th>Date</th>
    <th>Issue</th>
    <th>Brief note</th>
  </tr>
  <tr>
    <td>2023-05-31</td>
    <td><a href="https://jira.121ecommerce.co/browse/ECHO-90">ECHO-90</a></td>
    <td>Initial installation of extension.</td>
  </tr>
  <tr>
    <td>2023-06-02</td>
    <td><a href="https://jira.121ecommerce.co/browse/ECHO-90">ECHO-90</a></td>
    <td>Fixed company_form.xml select value not showing. </td>
  </tr>
  <tr>
    <td>2023-06-12</td>
    <td><a href="https://jira.121ecommerce.co/browse/ECHO-113">ECHO-113</a></td>
    <td>Make attribute 'default_warehouse_id' appear on API even if NULL. </td>
  </tr>
</table> 
