<?php
/**
 * MageSpecialist
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magespecialist.it so we can send you a copy immediately.
 *
 * @category   Grandcruwijnen
 * @package    Grandcruwijnen_APIEnhancer
 * @copyright  Copyright (c) 2017 Skeeller srl (http://www.magespecialist.it)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Grandcruwijnen\APIEnhancer\Observer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Model\ResourceModel\RuleFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;
use Grandcruwijnen\APIEnhancer\Api\CustomerAuthInterface;
use Grandcruwijnen\APIEnhancer\Api\TagInterface;

class CatalogProductCollectionLoadAfter implements ObserverInterface
{
    const XML_PATH_FIX_CATALOG_RULES = 'Grandcruwijnen_apienhancer/enhancements/fix_catalog_rules';

    /**
     * @var TagInterface
     */
    private $tag;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CustomerAuthInterface
     */
    private $customerAuth;

    /**
     * @var RuleFactory
     */
    private $resourceRuleFactory;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        TagInterface $tag,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        CustomerAuthInterface $customerAuth,
        TimezoneInterface $timezone,
        RuleFactory $resourceRuleFactory
    ) {
        $this->tag = $tag;
        $this->storeManager = $storeManager;
        $this->customerAuth = $customerAuth;
        $this->resourceRuleFactory = $resourceRuleFactory;
        $this->timezone = $timezone;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $collection = $observer->getCollection();

        $fixCatalogRules = !!$this->scopeConfig->getValue(static::XML_PATH_FIX_CATALOG_RULES);

        $tags = [Product::CACHE_TAG];
        $productIds = array_keys($collection->getItems());

        if ($fixCatalogRules) {
            if ($customer = $this->customerAuth->getCustomer()) {
                $groupId = $customer->getGroupId();
            } else {
                $groupId = 0;
            }
            $websiteId = $this->storeManager->getStore()->getWebsiteId();

            // FIX for rule price
            $rulePrices = $this->resourceRuleFactory->create()->getRulePrices(
                $this->timezone->date(),
                $websiteId,
                $groupId,
                $productIds
            );
        }

        foreach ($collection as $product) {
            /** @var ProductInterface $product */
            if ($fixCatalogRules && isset($rulePrices[$product->getId()])) {
                $product->setCustomAttribute('special_price', $rulePrices[$product->getId()]);
            }
            $tags[] = Product::CACHE_TAG.'_'.$product->getId();
        }

        $this->tag->addTags($tags);
    }
}
