<?php


namespace Magestore\POS\Model;


class LoginResult implements \Magestore\POS\Api\Data\LoginResultInterface
{
//
//    protected $staffFactory;
//
//    protected $posFactory;
//
//    public function __construct(
//        \Magestore\POS\Model\StaffFactory $staffFactory,
//        \Magestore\POS\Model\PosFactory $posFactory
//    )
//    {
//    }

    public $status;

    public $message;

    public $staff;

    public $listPost;

    public $code;

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setStaff($staff)
    {
        $this->staff = $staff;
    }

    public function getStaff()
    {
        return $this->staff;
    }

    public function setPListPos($listPos)
    {
        $this->listPost = $listPos;
    }

    public function getListPos()
    {
        return $this->listPost;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }
}
