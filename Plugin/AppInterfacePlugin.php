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

namespace Grandcruwijnen\APIEnhancer\Plugin;

use Closure;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\AppInterface;
use Grandcruwijnen\APIEnhancer\Api\CacheManagementInterface;
use Grandcruwijnen\APIEnhancer\Api\EnhancerManagementInterface;
use Grandcruwijnen\APIEnhancer\Api\VarnishManagementInterface;

class AppInterfacePlugin
{
    /**
     * @var EnhancerManagementInterface
     */
    private $enhancerManagement;

    /**
     * @var VarnishManagementInterface
     */
    private $varnishManagement;

    /**
     * @var CacheManagementInterface
     */
    private $cacheManagement;

    public function __construct(
        EnhancerManagementInterface $enhancerManagement,
        VarnishManagementInterface $varnishManagement,
        CacheManagementInterface $cacheManagement
    ) {
        $this->enhancerManagement = $enhancerManagement;
        $this->varnishManagement = $varnishManagement;
        $this->cacheManagement = $cacheManagement;
    }

    public function aroundLaunch(AppInterface $subject, Closure $proceed)
    {
        if ($this->enhancerManagement->isCacheEnabled() && $this->enhancerManagement->canCacheRequest()) {

            // Varnish mode
            if ($this->enhancerManagement->useVarnish()) {
                /** @var ResponseInterface $response */
                $response = $proceed();
                $this->varnishManagement->setVarnishHeaders($response);

                return $response;
            }

            // non-Varnish mode
            $response = $this->cacheManagement->getCacheResult();
            if (!$response) {
                /** @var ResponseInterface $response */
                $response = $proceed();
                $this->cacheManagement->setCacheResult($response);
            }
        } else {
            /** @var ResponseInterface $response */
            $response = $proceed();
        }

        return $response;
    }
}
