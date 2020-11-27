<?php

namespace Base\Models;

use Phalcon\Mvc\Model\ResultInterface;
use Phalcon\Mvc\Model\ResultSetInterface;
use Phalcon\Mvc\ModelInterface;

/**
 * Class Tenant
 *
 * @package Base\Models
 */
class Application extends BaseModel
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
     * @var string
     * @Column(column="name", type="string", nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(column="slug", type="string", nullable=false)
     */
    protected $slug;

    /**
     *
     * @var string
     * @Column(column="description", type="string", nullable=true)
     */
    protected $description;

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
     * Method to set the value of field description
     *
     * @param string|null $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('main_db');
        $this->setSchema("public");
        $this->setSource("application");

        $this->hasMany('id', Role::class, 'id_application', ['alias' => 'Role']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Application[]|Application|ResultSetInterface
     */
    public static function find($parameters = null): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Application|ResultInterface
     */
    public static function findFirst($parameters = null)
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
     * @return bool|ModelInterface
     */
    public static function getUserApplicationList(int $id_user)
    {
        return (new self)->modelsManager->createBuilder()
            ->addFrom(self::class, 'Tenant')
            ->innerJoin(Role::class, 'Role.id_application = Tenant.id', 'Role')
            ->innerJoin(UserRole::class, 'UserRole.id_role = Role.id', 'UserRole')
            ->where('UserRole.id_user = ?0', [ $id_user ])
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $application_slug
     *
     * @return bool|ModelInterface
     */
    public static function getBySlug(string $application_slug)
    {
        return self::findFirst([
            'conditions' => 'slug = ?0',
            'bind' => [ $application_slug ]
        ]);
    }
}
