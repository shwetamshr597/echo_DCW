<?php

declare(strict_types=1);

namespace Ecommerce121\PartSmartIntegration;

class Constants
{
    public const GROUP = 'Adobe';
    public const BUSINESS_NAME = 'Ecommerce121';
    public const API_URL_PREFIX = 'https://';
    public const API_BASE_URL = 'www.partsmart.net';

    public const ENABLE = 'ecommerce121_partsmart/general/enable';

    public const ENDPOINT_AUTHENTICATE = 'ecommerce121_partsmart/endpoints/authenticate';
    public const ENDPOINT_PORTAL_USER_TOKEN = 'ecommerce121_partsmart/endpoints/portal_user_token';

    public const USERNAME = 'ecommerce121_partsmart/credentials/username';
    public const PASSWORD = 'ecommerce121_partsmart/credentials/password';
    public const CREDENTIALS_EXPIRES_IN = 'ecommerce121_partsmart/credentials/expires_in';

    public const INFORMATION_FIRSTNAME = 'ecommerce121_partsmart/information/firstname';
    public const INFORMATION_LASTNAME = 'ecommerce121_partsmart/information/lastname';
    public const INFORMATION_STREET = 'ecommerce121_partsmart/information/street';
    public const INFORMATION_CITY = 'ecommerce121_partsmart/information/city';
    public const INFORMATION_POSTCODE = 'ecommerce121_partsmart/information/postcode';
    public const INFORMATION_STATE = 'ecommerce121_partsmart/information/state';
    public const INFORMATION_COUNTRY = 'ecommerce121_partsmart/information/country';

    public const EAV_CUSTOMER_ACCESS_TOKEN = 'part_smart_access_token';
    public const EAV_CUSTOMER_REFRESH_TOKEN = 'part_smart_refresh_token';
}