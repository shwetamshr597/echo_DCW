# Ecommerce121_RestrictCompanyCRUD

This extension will prevent a Company Admin be able to:
<li>Add a new user to the Company</li>
<li>Delete a user from the Company</li>
<li>Edit a company's user attributes other than Company Role</li>

#### Run PHP CodeSniffer

    vendor/bin/phpcs --standard=Magento2 --severity=6 app/code/Ecommerce121/RestrictCompanyCRUD/

#### Run PHP Copy-paste Detector

    vendor/bin/phpcpd app/code/Ecommerce121/RestrictCompanyCRUD/

#### Run PHP Mess Detector

    vendor/bin/phpmd app/code/Ecommerce121/RestrictCompanyCRUD/ text cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-results.txt

#### Run PHPStan

    vendor/bin/phpstan analyse --level=8 app/code/Ecommerce121/RestrictCompanyCRUD/

#### CHANGE LOG:
 <table>
  <tr>
    <th>Date</th>
    <th>Issue</th>
    <th>Brief note</th>
  </tr>
  <tr>
    <td>2023-06-02</td>
    <td><a href="https://jira.121ecommerce.co/browse/ECHO-94">ECHO-94</a></td>
    <td>Initial installation of extension.</td>
  </tr>
  <tr>
    <td>2023-06-12</td>
    <td><a href="https://jira.121ecommerce.co/browse/ECHO-115">ECHO-115</a></td>
    <td>Disable possibility to Company edit in frontend </td>
  </tr>
  <tr>
    <td>2023-06-12</td>
    <td><a href="https://jira.121ecommerce.co/browse/ECHO-118">ECHO-118</a></td>
    <td>Disable admin Company edit fields. </td>
  </tr>
  <tr>
    <td>2023-06-13</td>
    <td><a href="https://jira.121ecommerce.co/browse/ECHO-118">ECHO-118</a></td>
    <td>Prevent Company admin from accessing profile edit.</td>
  </tr>
</table> 
