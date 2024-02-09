<?php

declare(strict_types=1);

namespace Ecommerce121\ERPConnector\Helper;

use Ecommerce121\TibcoShipping\Helper\Exception;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Api\AddressRepositoryInterface;

class TibcoShipping extends AbstractHelper
{
    private const SUCCESS = 200;
    private const FREIGHT_CALCULATION_URL = 'ecommerce121_tibco_erp/general/freight_calculation_url';
    private const FREIGHT_BASE_URL = 'ecommerce121_tibco_erp/general/freight_base_url';
    private const FREIGHT_CALCULATION_USER_NAME = 'ecommerce121_tibco_erp/general/freight_calculation_user';
    private const FREIGHT_CALCULATION_PASSWORD = 'ecommerce121_tibco_erp/general/freight_calculation_pass';
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Session
     */
    private $customer;
    /**
     * @var CompanyRepositoryInterface
     */
    private $company;
    /**
     * @var \Ecommerce121\ERPConnector\Helper\ERPConnector
     */
    private $ERPConnector;
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;

    /**
     * @param CheckoutSession $checkoutSession
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $customer
     * @param CompanyRepositoryInterface $companyRepository
     * @param ERPConnector $ERPConnector
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        ScopeConfigInterface $scopeConfig,
        Session $customer,
        CompanyRepositoryInterface $companyRepository,
        ERPConnector $ERPConnector,
        AddressRepositoryInterface $addressRepository
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
        $this->customer = $customer;
        $this->company = $companyRepository;
        $this->ERPConnector = $ERPConnector;
        $this->addressRepository = $addressRepository;
    }

    /**
     * @return array|int[]
     */
    public function getFreightCalculation()
    {
        $data = ['price' => 0];
        $user = $this->getFreightCalculationUserName();
        $pass = $this->getFreightCalculationPassword();
        $baseUrl = $this->getFreightBaseUrl();
        $endpoint = $this->getFreightCalculationUrl();
        $body = $this->getDataFromCart();
        $response = $this->ERPConnector->makePostCall($body, $baseUrl, $endpoint, $user, $pass, 'POST');
        $result = json_decode((string)$response->getBody());
        if ($result->statusCode == self::SUCCESS) {
            $data = ['price' => $result->frtamt];
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getFreightCalculationUrl()
    {
        return $this->scopeConfig->getValue(self::FREIGHT_CALCULATION_URL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getFreightBaseUrl()
    {
        return $this->scopeConfig->getValue(self::FREIGHT_BASE_URL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getFreightCalculationUserName()
    {
        return $this->scopeConfig->getValue(self::FREIGHT_CALCULATION_USER_NAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getFreightCalculationPassword()
    {
        return $this->scopeConfig->getValue(self::FREIGHT_CALCULATION_PASSWORD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getDataFromCart()
    {
        $echoLocId = $this->getDefaultShippingAddress();
        $companyId = $this->customer->getCustomerData()->getExtensionAttributes()->getCompanyAttributes()->getCompanyId();
        $echoCustId = $this->company->get($companyId)->getExtensionAttributes()->getEchoCustId();

        $quote = $this->checkoutSession->getQuote();
        $cart['cust'] = $echoCustId;
        $cart['shipto'] = $echoLocId;
        $cart['nofitm'] = (int)$quote->getItemsCount();
        foreach ($quote->getAllVisibleItems() as $item) {
            $cart['items'][] = [
                'item' => $item->getSku(),
                'ordqty' => (int)$item->getQty(),
                'amount' => $item->getQty() * $item->getPrice(),
            ];
        }

        return $cart;
    }

    /**
     * @return \Magento\Customer\Model\Address|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getDefaultShippingAddress()
    {
        $defaultShippingAddress = $this->customer->getCustomer()->getDefaultShippingAddress();
        if ($defaultShippingAddress)
        {
            return $defaultShippingAddress->getData('echo_loc_id');;
        }

        $customerAddressId = $this->checkoutSession->getQuote()->getShippingAddress()->getData('customer_address_id');

        return $this->addressRepository->getById($customerAddressId)->getCustomAttribute('echo_loc_id')->getValue();
    }
}
