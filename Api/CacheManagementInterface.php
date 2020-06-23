<?php


namespace Grandcruwijnen\APIEnhancer\Api;

use Magento\Framework\App\ResponseInterface;

interface CacheManagementInterface
{
    /**
     * Get a cache response or false if not existing
     * @return ResponseInterface|false
     */
    public function getCacheResult();

    /**
     * Set a cache response for a request
     * @param ResponseInterface $response
     * @return void
     */
    public function setCacheResult(ResponseInterface $response);
}
