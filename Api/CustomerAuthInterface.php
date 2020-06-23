<?php


namespace Grandcruwijnen\APIEnhancer\Api;

use Magento\Customer\Api\Data\CustomerInterface;

interface CustomerAuthInterface
{
    /**
     * Get customer by oauth token
     * @return CustomerInterface|false
     */
    public function getCustomer();
}
