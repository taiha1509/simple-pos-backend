<?php


namespace Magestore\POS\Api\Data;


interface PosInterface
{
    /**
     * @param int $id
     * @return int
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return void
     */
    public function setDescription($description);

    /**
     * @return int
     */
    public function getLocation();

    /**
     * @param int $location
     * @return void
     */
    public function setLocation($location);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     * @return void
     */
    public function setStatus($status);

}
