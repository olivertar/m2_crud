<?php
namespace Orangecat\Crud\Api\Data;

interface SampleInterface
{
    const SAMPLE_ID      = 'sample_id';
    const TITLE         = 'title';
    const LINK         = 'link';
    const IMAGE         = 'image';
    const OPTIONS         = 'options';
    const CONTENT       = 'content';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';

    public function getId();

    public function getTitle();

    public function getLink();

    public function getImage();

    public function getOptions();

    public function getContent();

    public function getCreationTime();

    public function getUpdateTime();

    public function isActive();

    public function setId($id);

    public function setTitle($title);

    public function setLink($link);

    public function setImage($image);

    public function setOptions($options);

    public function setContent($content);

    public function setCreationTime($creationTime);

    public function setUpdateTime($updateTime);

    public function setIsActive($isActive);
}
