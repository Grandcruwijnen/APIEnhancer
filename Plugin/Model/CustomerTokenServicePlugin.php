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

namespace Grandcruwijnen\APIEnhancer\Plugin\Model;

use Magento\Integration\Model\CustomerTokenService;
use Grandcruwijnen\APIEnhancer\Api\VarnishManagementInterface;

class CustomerTokenServicePlugin
{
    /**
     * @var VarnishManagementInterface
     */
    private $varnishManagement;

    public function __construct(
        VarnishManagementInterface $varnishManagement
    ) {
        $this->varnishManagement = $varnishManagement;
    }

    public function afterCreateCustomerAccessToken(CustomerTokenService $subject, $result)
    {
        if ($result) {
            return $this->varnishManagement->getVaryToken($result);
        }

        return $result;
    }
}
