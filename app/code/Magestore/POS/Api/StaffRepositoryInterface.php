<?php


namespace Magestore\POS\Api;


interface StaffRepositoryInterface
{
    /**
     * @param mixed $data
     * @return \Magestore\POS\Api\Data\LoginResultInterface
     */
    public function login($data);

    /**
     * @param $data
     * @return mixed
     */
    public function authorize($data);
}
