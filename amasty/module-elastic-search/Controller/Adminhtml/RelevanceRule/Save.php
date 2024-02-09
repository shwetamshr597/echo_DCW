<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Elastic Search Base for Magento 2
 */

namespace Amasty\ElasticSearch\Controller\Adminhtml\RelevanceRule;

use Amasty\ElasticSearch\Api\Data\RelevanceRuleInterface;
use Amasty\ElasticSearch\Model\Source\RelevanceRuleModificationType;
use Magento\Framework\Exception\LocalizedException;

class Save extends AbstractRelevance
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $rule = (int)$this->getRequest()->getParam(RelevanceRuleInterface::RULE_ID);
        $data = $this->getRequest()->getPostValue();

        try {
            if ($rule) {
                /** @var  \Amasty\ElasticSearch\Model\RelevanceRule $model */
                $model = $this->ruleRepository->get($rule);
            } else {
                $model = $this->ruleFactory->create();
            }

            $data = $this->prepareData($data, $model);
            $model->setData($data);
            $this->ruleRepository->save($model);
            $this->messageManager->addSuccessMessage(__('You have saved the Rule.'));
        } catch (\Magento\Framework\Exception\AlreadyExistsException $e) {
            $this->messageManager->addErrorMessage(
                __('Relevance Rule with the same term already exists in an associated store.')
            );
        } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage(__('This Relevance Rule no longer exists.'));
            $resultRedirect = $this->resultRedirectFactory->create();
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param array $data
     * @param \Amasty\ElasticSearch\Model\RelevanceRule $rule
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function prepareData(array $data, \Amasty\ElasticSearch\Model\RelevanceRule $rule)
    {
        if (empty($data[RelevanceRuleInterface::RULE_ID])) {
            $data[RelevanceRuleInterface::RULE_ID] = null;
        }

        $data[RelevanceRuleInterface::MULTIPLIER] = max(
            min($data[RelevanceRuleInterface::MULTIPLIER], 100),
            1
        );

        if ($data['modification_type'] === RelevanceRuleModificationType::DECREASE) {
            $data[RelevanceRuleInterface::MULTIPLIER] *= -1;
        }

        if (isset($data['rule']) && isset($data['rule']['conditions'])) {
            $conditions = $data['rule']['conditions'];
            unset($data['rule']);
            $data[RelevanceRuleInterface::CONDITIONS] = $rule->getConditionsSerialized($conditions);
        }

        $data = $this->prepareRuleStatus($data);

        return $data;
    }

    private function prepareRuleStatus(array $data): array
    {
        $websiteId = $data[RelevanceRuleInterface::WEBSITE_ID] ?? false;

        if ($websiteId) {
            try {
                $website = $this->storeManager->getWebsite($websiteId);

                if (!empty($data[RelevanceRuleInterface::FROM_DATE])
                    && !empty($data[RelevanceRuleInterface::TO_DATE])
                    && !$this->timezone->isScopeDateInInterval(
                        $website,
                        $data[RelevanceRuleInterface::FROM_DATE],
                        $data[RelevanceRuleInterface::TO_DATE]
                    )
                ) {
                    $data[RelevanceRuleInterface::IS_ENABLED] = false;
                }
            } catch (LocalizedException $e) {
                ;// do nothing
            }
        }

        return $data;
    }
}
