<?php

declare(strict_types=1);

namespace Ecommerce121\PartSmartIntegration\Model;

use Ecommerce121\PartSmartIntegration\Constants;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $writer
     */
    public function __construct(
        protected readonly ScopeConfigInterface $scopeConfig,
        protected readonly WriterInterface $writer
    ) {}

    /**
     * @param string $path
     * @return mixed
     */
    private function getConfig(string $path): mixed
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function isEnabled(): mixed
    {
        return $this->getConfig(Constants::ENABLE);
    }

    /**
     * @return mixed
     */
    public function getUsername(): mixed
    {
        return $this->getConfig(Constants::USERNAME);
    }

    /**
     * @return mixed
     */
    public function getPassword(): mixed
    {
        return $this->getConfig(Constants::PASSWORD);
    }

    /**
     * @return string
     */
    public function getCredentialPayload(): string
    {
        $payload = [
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
        ];

        return json_encode($payload);
    }

    /**
     * @param $customer
     * @return string
     */
    public function getUserPortalCredentialPayload($customer): string
    {
        $firstName = $customer->getFirstname();
        $lastName = $customer->getLastname();
        $email = $customer->getEmail();
        $username = $customer->getId() . '_' . $firstName . '-' . $lastName;

        $customerAddress = $this->getCustomerAddress($customer);
        if (is_array($customerAddress)) {
            $street = $customerAddress[0]->getStreet()[0];
            $city = $customerAddress[0]->getCity();
            $region = $customerAddress[0]->getRegion()->getRegionCode();
            $country = $customerAddress[0]->getCountryId();
            $postcode = $customerAddress[0]->getPostcode();
        } else {
            $street = $this->getDefaultStreet();
            $city = $this->getDefaultCity();
            $region = $this->getDefaultState();
            $country = $this->getDefaultCountry();
            $postcode = $this->getDefaultPostcode();
        }

        $payload = [
            'username' => $username,
            'email' => $email,
            'businessName' => Constants::BUSINESS_NAME,
            'groupCode' => Constants::GROUP,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'streetAddress' => $street,
            'city' => $city,
            'stateProvince' => $region,
            'postalCode' => $postcode,
            'country' => $country
        ];

        return json_encode($payload);
    }

    /**
     * @return mixed
     */
    public function getAuthenticateEndpoint(): mixed
    {
        return $this->getConfig(Constants::ENDPOINT_AUTHENTICATE);
    }

    /**
     * @return mixed
     */
    public function getPortalUserEndpoint(): mixed
    {
        return $this->getConfig(Constants::ENDPOINT_PORTAL_USER_TOKEN);
    }

    /**
     * @return mixed
     */
    public function getExpiresIn(): mixed
    {
        return $this->getConfig(Constants::CREDENTIALS_EXPIRES_IN) ?? 10800;
    }

    /**
     * @return mixed
     */
    public function getFirstname(): mixed
    {
        return $this->getConfig(Constants::INFORMATION_FIRSTNAME);
    }

    /**
     * @return mixed
     */
    public function getLastname(): mixed
    {
        return $this->getConfig(Constants::INFORMATION_LASTNAME);
    }

    /**
     * @return mixed
     */
    public function getDefaultStreet(): mixed
    {
        return $this->getConfig(Constants::INFORMATION_STREET);
    }

    /**
     * @return mixed
     */
    public function getDefaultCity(): mixed
    {
        return $this->getConfig(Constants::INFORMATION_CITY);
    }

    /**
     * @return mixed
     */
    public function getDefaultPostcode(): mixed
    {
        return $this->getConfig(Constants::INFORMATION_POSTCODE);
    }

    /**
     * @return mixed
     */
    public function getDefaultState(): mixed
    {
        return $this->getConfig(Constants::INFORMATION_STATE);
    }

    /**
     * @return mixed
     */
    public function getDefaultCountry(): mixed
    {
        return $this->getConfig(Constants::INFORMATION_COUNTRY);
    }

    /**
     * @param $path
     * @param $value
     * @return void
     */
    public function setConfig($path, $value): void
    {
        $this->writer->save($path, $value);
    }

    /**
     * @param $customer
     */
    public function getCustomerAddress($customer)
    {
        $customerAddress = [];
        if ($customer->getAddresses() != null) {
            foreach ($customer->getAddresses() as $address) {
                $customerAddress[] = $address;
            }

            return $customerAddress;
        }

        return false;
    }
}