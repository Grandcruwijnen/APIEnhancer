<?php


namespace Grandcruwijnen\APIEnhancer\Model;

use Grandcruwijnen\APIEnhancer\Api\TagInterface;

class Tag implements TagInterface
{
    protected $tags = [];

    /**
     * Return a list of collected tags
     * @return array
     */
    public function getTags()
    {
        return array_unique($this->tags);
    }

    /**
     * Return a list of collected tags
     * @param array $tags
     * @return $this
     */
    public function addTags(array $tags)
    {
        $this->tags = array_merge($this->tags, $tags);

        return $this;
    }
}
