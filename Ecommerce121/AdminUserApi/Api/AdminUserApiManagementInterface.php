<?php
declare(strict_types=1);

namespace Ecommerce121\AdminUserApi\Api;

interface AdminUserApiManagementInterface
{

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $username
     * @param string $password
     * @param int|null $roleId
     * @return mixed
     */
    public function postAdminUserApi(
        string $firstname,
        string $lastname,
        string $email,
        string $username,
        string $password,
        int $roleId = null
    );

    /**
     * @param string|null $firstname
     * @param string|null $lastname
     * @param string|null $email
     * @param string|null $username
     * @param string|null $password
     * @param int|null $roleId
     * @param int|null $isActive
     * @return mixed
     */
    public function putAdminUserApi(
        string $firstname = null,
        string $lastname = null,
        string $email = null,
        string $username = null,
        string $password = null,
        int $roleId = null,
        int $isActive = null
    );

    /**
     * @return mixed
     */
    public function getAdminUserApi();
}
