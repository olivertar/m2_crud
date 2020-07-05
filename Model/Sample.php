<?php
namespace Orangecat\Crud\Model;

use Orangecat\Crud\Api\Data\SampleInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Sample extends AbstractModel implements SampleInterface, IdentityInterface
{
    const CACHE_TAG = 'crud_s';

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const OPTION_0 = 0;
    const OPTION_A = 1;
    const OPTION_B = 2;
    const OPTION_C = 3;

    protected $_cacheTag = self::CACHE_TAG;

    protected $_eventPrefix = 'crud_sample';

    protected function _construct()
    {
        $this->_init(\Orangecat\Crud\Model\ResourceModel\Sample::class);
    }

    public function beforeSave()
    {
        if ($this->hasDataChanges()) {
            $this->setUpdateTime(null);
        }

        $needle = 'sample_id="' . $this->getId() . '"';
        if (false == strstr($this->getContent(), (string) $needle)) {
            return parent::beforeSave();
        }
        throw new \Magento\Framework\Exception\LocalizedException(
            __('Make sure that sample content does not reference the sample itself.')
        );
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    public function getId()
    {
        return $this->getData(self::SAMPLE_ID);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    public function getLink()
    {
        return $this->getData(self::LINK);
    }

    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    public function getOptions()
    {
        return $this->getData(self::OPTIONS);
    }

    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    public function setId($id)
    {
        return $this->setData(self::SAMPLE_ID, $id);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function setLink($link)
    {
        return $this->setData(self::LINK, $link);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function setOptions($options)
    {
        return $this->setData(self::OPTIONS, $options);
    }

    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getAvailableOptions()
    {
        return [self::OPTION_0 => __('No Options'), self::OPTION_A => __('Option #1'), self::OPTION_B => __('Option #2'), self::OPTION_C => __('Option #3')];
    }
}
