<?php
namespace Orangecat\Crud\Model\ResourceModel\Sample;

use Orangecat\Crud\Api\Data\SampleInterface;
use \Orangecat\Crud\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'sample_id';

    protected $_eventPrefix = 'crud_sample_collection';

    protected $_eventObject = 'sample_collection';

    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(SampleInterface::class);

        $this->performAfterLoad('crud_sample_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    protected function _construct()
    {
        $this->_init(\Orangecat\Crud\Model\Sample::class, \Orangecat\Crud\Model\ResourceModel\Sample::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['sample_id'] = 'main_table.sample_id';
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('sample_id', 'title');
    }

    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(SampleInterface::class);
        $this->joinStoreRelationTable('crud_sample_store', $entityMetadata->getLinkField());
    }
}
