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

use Magento\Catalog\Model\Category;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Grandcruwijnen\APIEnhancer\Api\TagInterface;

class CatalogCategoryCollectionLoadAfter implements ObserverInterface
{
    /**
     * @var TagInterface
     */
    private $tag;

    public function __construct(
        TagInterface $tag
    ) {
        $this->tag = $tag;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $collection = $observer->getCategoryCollection();
        $tags = [Category::CACHE_TAG];
        $ids = array_keys($collection->getItems());

        foreach ($ids as $id) {
            $tags[] = Category::CACHE_TAG.'_'.$id;
        }

        $this->tag->addTags($tags);
    }
}
