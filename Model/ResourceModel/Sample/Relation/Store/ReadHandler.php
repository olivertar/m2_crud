<?php
namespace Orangecat\Crud\Model\ResourceModel\Sample\Relation\Store;

use Orangecat\Crud\Model\ResourceModel\Sample;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class ReadHandler implements ExtensionInterface
{
    protected $resourceSample;

    public function __construct(
        Sample $resourceSample
    ) {
        $this->resourceSample = $resourceSample;
    }

    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $stores = $this->resourceSample->lookupStoreIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
            $entity->setData('stores', $stores);
        }
        return $entity;
    }
}
