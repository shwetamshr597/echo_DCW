# Module Email Test Mode
The extension provides a functionality to send transactional emails in test mode.

No emails are sent to real customer addresses on not production environment only to the configured addresses.
# Usage
The test mode can be enabled by the following path: 

`Stores -> Configuration -> Advanced -> System -> Mail Sending Settings`

When **Disable Email Communications** is set **NO** the **Enable test mode** field appears.

After **Enable test mode** is set to **YES** the **Test Emails** field has to be populated with at least one valid email address. 

# Installation Instructions
WARNING: We recommend you backup your Magento installation and database immediately prior to installing any extension. We do not take any responsibility for issues caused due to not taking this precaution.

Follow this steps:

1. After downloading the extension, unzip to a local directory.

2. Copy the extension folder onto your app/code magento directory.

3. Run commands:

    3.1. bin/magento module:enable Ecommerce121_EmailTestMode

    3.2. bin/magento setup:upgrade

    3.3. bin/magento setup:di:compile

    3.4. bin/magento setup:static-content-deploy
   
# Copyright
Copyright © 121eCommerce (https://www.121ecommerce.com)
