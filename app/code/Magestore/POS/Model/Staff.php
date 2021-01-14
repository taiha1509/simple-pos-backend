<?php


namespace Magestore\POS\Model;
use Firebase\JWT\JWT;


class Staff extends \Magento\Framework\Model\AbstractModel implements \Magestore\POS\Api\Data\StaffInterface
{
    protected $_encryptorInterface;

    protected $sessionFactory;

    protected $secret_key = "mywebpos";

    protected $signAlgorithm = "HS256";

    protected $posCollectionFactory;

    protected $loginResultFactory;

    /**
     * Staff constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface
     * @param \Magestore\POS\Model\SessionFactory $sessionFactory
     * @param \Magestore\POS\Model\ResourceModel\Pos\CollectionFactory $posCollectionFactory
     * @param \Magestore\POS\Model\LoginResultFactory $loginResultFactory
     */
   public function __construct(
       \Magento\Framework\Model\Context $context,
       \Magento\Framework\Registry $registry,
       \Magestore\POS\Model\ResourceModel\Staff $resource,
       \Magestore\POS\Model\ResourceModel\Staff\Collection $resourceCollection,
       array $data = [],
       \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
       \Magestore\POS\Model\SessionFactory $sessionFactory,
       \Magestore\POS\Model\ResourceModel\Pos\CollectionFactory $posCollectionFactory,
       \Magestore\POS\Model\LoginResultFactory $loginResultFactory
   )
   {
       $this->posCollectionFactory = $posCollectionFactory;
       $this->sessionFactory = $sessionFactory;
       $this->_encryptorInterface = $encryptorInterface;
       $this->loginResultFactory = $loginResultFactory;
       parent::__construct($context, $registry, $resource, $resourceCollection, $data);
   }

    public function beforeSave()
    {
        $dateTime = new \DateTime('now');
        //var_dump($dateTime->gmtDate());
        if (!$this->getId()) {
            $this->setCreatedAt($dateTime);
        } else {
            $this->setUpdatedAt($dateTime);
        }

        return parent::beforeSave();
    }

    /**
     * @param string $password
     * @return string
     */
    public function getHashPassword($password){
        $hash = $this->_encryptorInterface->getHash($password);
        return $hash;
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword($password, $hash){
        return $this->_encryptorInterface->validateHash($password, $hash);
    }

    /**
     * get staff by email and check password is correct if email is exist
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function getStaffLoginResult($data){
        $email = $data['email'];
        $password = $data['password'];
        $joinConditions = 'main_table.id = m2POS_session.staff_id';
        $collection = $this->getCollection();
        $collection->getSelect()->join(
            $this->getResource()->getTable('m2POS_session'),
            $joinConditions
        )->where('main_table.email = \''.$email.'\'');

        //show sql query
//        var_dump($collection->getSelect()->__toString());

        if(!$collection->getData() || !$email || !$password){

            return $this->setDataResponse(0,400, 'unAuthorized');
        }

        else{
            // check password
            $isPasswordMatch = $this->verifyPassword($password, $collection->getData()[0]['password']);
            //staff array information
            $staff = $collection->getData()[0];
            if(!$isPasswordMatch){
                return $this->setDataResponse(0,400, 'unAuthorized');
            }else{
                // generate token for staff session
                $sessionModel = $this->sessionFactory->create();
                // check if session record of this staff is exist
                $record = $sessionModel->findByStaffId($staff['staff_id']);

                if($record){
                    $sessionId = $record['id'];
                    $sessionModel = $sessionModel->load($sessionId);
                }
                // set data
                $sessionModel->setData('token', $this->genToken($staff['email'], $staff['password']));
                $sessionModel->setData('expired', 0);
                $sessionModel->setData('staff_id', $staff['staff_id']);
                $sessionModel->save();

                //get list pos location of this staff
                $posCollection = $this->posCollectionFactory->create();
                $posCollection->getSelect()->join(
                    'm2POS_staff_pos',
                    'main_table.id = m2POS_staff_pos.pos_id'
                )->where('staff_id = '.$staff['staff_id']);


//                var_dump($posCollection->getData());
//                die;
                $this->load($staff['staff_id']);
                $this->setToken($sessionModel->getData('token'));

                return $this->setDataResponse(1, 200, 'login successfully', $this, $this->getListPosByStaffId($staff['staff_id']));
            }
        }

    }

    /**
     * @param int $status
     * @param int $code
     * @param string $message
     * @param \Magestore\POS\Api\Data\StaffInterface $staff
     * @param \Magestore\POS\Api\Data\PosInterface[] $listPos
     * @return LoginResult
     */
    protected function setDataResponse($status, $code, $message, $staff = null, $listPos =null){
        $loginResultFactory = $this->loginResultFactory->create();
        $loginResultFactory->setCode($code);
        $loginResultFactory->setStatus($status);
        $loginResultFactory->setPListPos($listPos);
        $loginResultFactory->setStaff($staff);
        $loginResultFactory->setMessage($message);
        return $loginResultFactory;
    }

    /**
     * @param int $id
     * @return \Magestore\POS\Model\Pos[]
     */
    public function getListPosByStaffId($id){
        $posCollection = $this->posCollectionFactory->create();
        $posCollection->getSelect()->join(
            'm2POS_staff_pos',
            'main_table.id = m2POS_staff_pos.pos_id'
        )->where('staff_id = '.$id);
        $result = array();
        foreach ($posCollection as $item){
            array_push($result, $item);
        }
        return $result;
    }

    /**
     * @param string $email
     * @param string $password
     * @return string
     */
    public function genToken($email, $password){

        $payload = array(
            'email' => $email,
            'password' => $password
        );
        return JWT::encode($payload, $this->secret_key);

    }


    /**
     * @param string $token
     * @return array
     */
    public function decodeToken($token){
        $decoded = JWT::decode($token, $this->secret_key, array($this->signAlgorithm));
        $decoded_array = (array) $decoded;
        return $decoded_array;
    }

    /**
     * @inheritDoc
     */
    public function authorize($staff_id, $token){
        $sessionModel = $this->sessionFactory->create();
        $collection = $sessionModel->getCollection();
        $collection->addFieldToFilter('staff_id', array('eq' => $staff_id));
        if($collection->getSize() < 1){
            return false;
        }
        else if($token == $collection->getData()[0]['token'] && $collection->getData()[0]['staff_id'] == $staff_id)
            return true;
        return false;
    }

    public function setName($name)
    {
        $this->setData('name', $name);
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function getEmail()
    {
        return $this->getData('email');
    }

    public function setEmail($email)
    {
        $this->setData('email', $email);
    }

    public function setPassword($password)
    {
        $this->setData('password', $password);
    }

    public function getPassword()
    {
        return $this->getData('password');
    }

    public function setStatus($status)
    {
        $this->setData('status', $status);
    }

    public function getStatus()
    {
        return $this->getData('status');
    }

    public function setToken($token)
    {
        return $this->setData('token', $token);
    }

    public function getToken()
    {
        return $this->getData('token');
    }
}
