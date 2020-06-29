<?php
namespace Orangecat\Crud\Model\Config\Source\Wysiwyg;

class Editor implements \Magento\Framework\Option\ArrayInterface
{
    private $adapterOptions;

    public function __construct(array $adapterOptions = [])
    {
        $this->adapterOptions = $adapterOptions;
    }

    public function toOptionArray()
    {
        return $this->adapterOptions;
    }
}
