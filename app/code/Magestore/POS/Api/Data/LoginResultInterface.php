<?php


namespace Magestore\POS\Api\Data;



interface LoginResultInterface
{
    /**
     * @param int $status
     * @return void
     */
    public function setStatus($status);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param string $message
     * @return void
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param \Magestore\POS\Api\Data\StaffInterface $staff
     * @return void
     */
    public function setStaff($staff);

    /**
     * @return \Magestore\POS\Api\Data\StaffInterface
     */
    public function getStaff();

    /**
     * @param \Magestore\POS\Api\Data\PosInterface[] $listPos
     * @return void
     */
    public function setPListPos($listPos);

    /**
     * @return \Magestore\POS\Api\Data\PosInterface[]
     */
    public function getListPos();

    /**
     * @param int $code
     * @return void
     */
    public function setCode($code);

    /**
     * @return int
     */
    public function getCode();

}
