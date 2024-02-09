<?php
declare(strict_types=1);

namespace Ecommerce121\AdminUserApi\Model;

use Ecommerce121\AdminUserApi\Api\AdminUserApiManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\User\Model\UserFactory;
use Magento\Framework\Webapi\Authorization;
use Magento\Framework\Webapi\ErrorProcessor;
use Magento\Framework\Webapi\Response;
use Magento\Framework\Webapi\Rest\Request;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

class AdminUserApiManagement implements AdminUserApiManagementInterface
{
    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var Authorization
     */
    private $authorization;

    /**
     * @var ErrorProcessor
     */
    private $errorProcessor;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Request
     */
    private $request;
    /**
     * @var UserCollectionFactory
     */
    private $userCollectionFactory;

    /**
     * @param UserFactory $userFactory
     * @param Authorization $authorization
     * @param ErrorProcessor $errorProcessor
     * @param Response $response
     * @param Request $request
     * @param UserCollectionFactory $userCollectionFactory
     */
    public function __construct(
        UserFactory $userFactory,
        Authorization $authorization,
        ErrorProcessor $errorProcessor,
        Response $response,
        Request $request,
        UserCollectionFactory $userCollectionFactory
    ) {
        $this->userFactory = $userFactory;
        $this->authorization = $authorization;
        $this->errorProcessor = $errorProcessor;
        $this->response = $response;
        $this->request = $request;
        $this->userCollectionFactory = $userCollectionFactory;
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $username
     * @param string $password
     * @param int|null $roleId
     * @return array|string
     */
    public function postAdminUserApi(
        string $firstname,
        string $lastname,
        string $email,
        string $username,
        string $password,
        int $roleId = null
    ) {
        $roleId = isset($roleId) ? (int) $roleId : 1;
        try {
            $user = $this->userFactory->create();
            $user->setFirstName($firstname);
            $user->setLastName($lastname);
            $user->setEmail($email);
            $user->setUserName($username);
            $user->setPassword($password);
            $user->setRoleId($roleId);
            $user->save();
            return $this->getAllUsers();
        } catch (\Exception $e) {
            return 'Error creating admin user: ' . $e->getMessage();
        }
    }

    /**
     * @param string|null $firstname
     * @param string|null $lastname
     * @param string|null $email
     * @param string|null $username
     * @param string|null $password
     * @param int|null $roleId
     * @param int|null $isActive
     * @return string[]
     * @throws LocalizedException
     */
    public function putAdminUserApi(
        string $firstname = null,
        string $lastname = null,
        string $email = null,
        string $username = null,
        string $password = null,
        int $roleId = null,
        int $isActive = null
    ) {
        try {
            $userId = $this->request->getParam('id');

            if (!$userId) {
                throw new LocalizedException(__('Admin user ID is missing.'));
            }

            $user = $this->userFactory->create()->load($userId);

            if (!$user->getId()) {
                throw new LocalizedException(__('Admin user with ID %1 does not exist.', $userId));
            }

            if (isset($firstname)) {
                $user->setFirstname($firstname);
            }

            if (isset($lastname)) {
                $user->setLastname($lastname);
            }

            if (isset($email)) {
                $user->setEmail($email);
            }

            if (isset($username)) {
                $user->setUserName($username);
            }

            if (isset($password)) {
                $user->setPassword($password);
            }

            if (isset($roleId)) {
                $user->setRoleId($roleId);
            }

            if (isset($isActive)) {
                $user->setIsActive($isActive);
            }

            $user->save();

            return [
                'id' => 'id ' . $user->getId(),
                'name' => 'name ' . $user->getFirstname(),
                'lastname' => 'lastname ' . $user->getLastName(),
            ];
        } catch (\Exception $e) {
            throw new LocalizedException(__('Could not update the admin user: %1', $e->getMessage()));
        }
    }

    /**
     * @return array
     */
    protected function getAllUsers()
    {
        $userCollection = $this->userCollectionFactory->create();
        $userCollection->addFieldToFilter('is_active', 1);
        $adminUsersData = [];
        foreach ($userCollection as $user) {
            $adminUsersData[] = [
                'id' => $user->getId(),
                'name' => $user->getFirstname(),
                'lastname' => $user->getLastName(),
            ];
        }
        return $adminUsersData;
    }

    /**
     * @return mixed|string|true
     * @throws LocalizedException
     */
    public function getAdminUserApi()
    {
        try {
            $userId = $this->request->getParam('id');
            if (!$userId) {
                throw new LocalizedException(__('Admin user ID is missing.'));
            }
            $user = $this->userFactory->create()->load($userId);
            $userData = $user->getData();
            if (empty($userData)) {
                throw new LocalizedException(__('Admin user does not exist.'));
            }

            return [
                'id' => "id: " . $user->getId(),
                'name' => "name: " . $user->getFirstname(),
                'lastname' => "lastname: " . $user->getLastName(),
                'email' => "email: " . $user->getEmail(),
                'username' => "username: " . $user->getUserName(),
                'created' => "created: " . $user->getCreated(),
                'modified' => "modified: " . $user->getModified(),
                'is_active' => "is_active: " . $user->getIsActive(),
                'role_id' => "role_id: " . $user->getRole()->getId(),
            ];
        } catch (\Exception $e) {
            throw new LocalizedException(__('Could not update the admin user: %1', $e->getMessage()));
        }
    }
}
