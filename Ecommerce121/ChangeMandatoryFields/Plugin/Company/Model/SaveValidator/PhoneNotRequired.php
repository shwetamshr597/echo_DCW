<?php

declare(strict_types=1);

namespace Ecommerce121\ChangeMandatoryFields\Plugin\Company\Model\SaveValidator;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\SaveValidator\RequiredFields;
use Magento\Framework\Exception\InputException;

class PhoneNotRequired
{
    /**
     * @var array<mixed>
     */
    private array $requiredFields = [
        CompanyInterface::NAME,
        CompanyInterface::COMPANY_EMAIL,
        CompanyInterface::STREET,
        CompanyInterface::CITY,
        CompanyInterface::POSTCODE,
        CompanyInterface::COUNTRY_ID,
        CompanyInterface::SUPER_USER_ID,
        CompanyInterface::CUSTOMER_GROUP_ID
    ];

    /**
     * PhoneNotRequired constructor
     *
     * @param CompanyInterface $company
     * @param InputException $exception
     */
    public function __construct(
        private readonly CompanyInterface $company,
        private readonly InputException   $exception
    ) {
    }

    /**
     * Around execute plugin
     *
     * @param RequiredFields $subject
     * @param callable $proceed
     * @return void
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function aroundExecute(RequiredFields $subject, callable $proceed): void
    {
        foreach ($this->requiredFields as $field) {
            // @phpstan-ignore-next-line
            if (empty($this->company->getData($field))) {
                $this->exception->addError(
                    __(
                        '"%fieldName" is required. Enter and try again.',
                        ['fieldName' => $field]
                    )
                );
            }
        }
    }
}
