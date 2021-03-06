<?php

namespace Base\Models;

use Phalcon\Mvc\Model\ResultInterface;
use Phalcon\Mvc\Model\ResultSetInterface;
use Phalcon\Mvc\ModelInterface;

/**
 * Class Role
 *
 * @package Base\Models
 */
class Role extends BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(column="id_application", type="integer", nullable=false)
     */
    protected $id_application;

    /**
     *
     * @var string
     * @Column(column="name", type="string", nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(column="slug", type="string", nullable=true)
     */
    protected $slug;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field id_application
     *
     * @param integer $id_application
     * @return $this
     */
    public function setIdApplication($id_application)
    {
        $this->id_application = $id_application;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field slug
     *
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_application
     *
     * @return integer
     */
    public function getIdApplication()
    {
        return $this->id_application;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('main_db');
        $this->setSource("role");

        $this->hasMany('id', UserRole::class, 'id_role', ['alias' => 'UserRole']);
        $this->hasOne('id_application', Application::class, 'id', ['alias' => 'Tenant']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Role[]|Role|ResultSetInterface
     */
    public static function find($parameters = null): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Role|ResultInterface
     */
    public static function findFirst($parameters = null): ?ModelInterface
    {
        return parent::findFirst($parameters);
    }


    /**********************************************************
     *
     *                         FUNCTIONS
     *
     **********************************************************/

    /**
     * @param int $id_user
     * @param int $id_application
     * @return Role|null
     */
    public static function getUserRole(int $id_user, int $id_application): ?Role
    {
        return (new self)->modelsManager->createBuilder()
            ->addFrom(self::class, 'Role')
            ->innerJoin(UserRole::class, 'Role.id = UserRole.id_role', 'UserRole')
            ->where('UserRole.id_user = ?1 AND Role.id_application = ?2', [
                1 => $id_user,
                2 => $id_application
            ])
            ->getQuery()
            ->execute()
            ->getFirst();
    }

}
