<?php


namespace Magestore\POS\Model;
use Magestore\POS\Model;

class StaffRepository implements \Magestore\POS\Api\StaffRepositoryInterface
{

    protected $staffFactory;

    protected $sessionFactory;

    protected $request;

    public function __construct(
        StaffFactory $staff,
        SessionFactory $session,
        \Magento\Framework\Webapi\Rest\Request $request
    )
    {
        $this->request = $request;
        $this->sessionFactory = $session;
        $this->staffFactory = $staff;
    }

    /**
     * @inheritDoc
     */
    public function login($data)
    {
        return $this->staffFactory->create()->getStaffLoginResult($data);
    }

    /**
     * @inheritDoc
     */
    public function authorize($data)
    {
        var_dump($data);
        die;
        $staffModel = $this->staffFactory->create();
        return $this->staffFactory->create()->authorize('2', '1');
    }
}
