<?php


namespace Magestore\POS\Model;


class Pos extends \Magento\Framework\Model\AbstractModel implements \Magestore\POS\Api\Data\PosInterface
{
//    protected $_resourceName = \Magestore\POS\Model\ResourceModel\Pos::class;

    protected function _construct()
    {
        $this->_init(\Magestore\POS\Model\ResourceModel\Pos::class);
        parent::_construct();
    }

    public function beforeSave()
    {
        $dateTime = new \DateTime('now');
        //var_dump($dateTime->gmtDate());
        if (!$this->getId()) {
            $this->setCreatedAt($dateTime);
        } else {
            $this->setUpdated_at($dateTime);
        }

        return parent::beforeSave();
    }

    public function setName($name)
    {
        $this->setData('name', $name);
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function getDescription()
    {
        return $this->getData('description');
    }

    public function setDescription($description)
    {
        $this->setData('description', $description);
    }

    public function getLocation()
    {
        return $this->getData('location');
    }

    public function setLocation($location)
    {
        $this->setData('location', $location);
    }

    public function getStatus()
    {
        return $this->getData('status');
    }

    public function setStatus($status)
    {
        $this->setData('status', $status);
    }

    public function setId($id)
    {
        $this->setData('id', $id);
    }

    public function getId()
    {
        return $this->getData('id');
    }
}
