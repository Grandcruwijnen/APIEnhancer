<?php


namespace Grandcruwijnen\APIEnhancer\Api;


interface CacheKeyProcessorInterface
{
    /**
     * Return a list of cache keys for a request
     * @return array
     */
    public function getKeys();
}
