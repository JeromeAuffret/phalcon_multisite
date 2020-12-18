<?php

namespace Base\Models;

use Phalcon\Mvc\Model\ResultInterface;
use Phalcon\Mvc\Model\ResultSetInterface;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Class User
 *
 * @package Base\Models
 */
class User extends BaseModel
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
     * @Column(column="login", type="string", nullable=false)
     */
    protected $login;

    /**
     *
     * @var string
     * @Column(column="firstname", type="string", nullable=true)
     */
    protected $firstname;

    /**
     *
     * @var string
     * @Column(column="lastname", type="string", nullable=true)
     */
    protected $lastname;

    /**
     *
     * @var string
     * @Column(column="email", type="string", nullable=true)
     */
    protected $email;

    /**
     *
     * @var string
     * @Column(column="password", type="string", nullable=false)
     */
    protected $password;

    /**
     *
     * @var string
     * @Column(column="created_at", type="string", nullable=false)
     */
    protected $created_at;

    /**
     *
     * @var string
     * @Column(column="connected_at", type="string", nullable=true)
     */
    protected $connected_at;

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
     * Method to set the value of field login
     *
     * @param string $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Method to set the value of field firstname
     *
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Method to set the value of field lastname
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field connected_at
     *
     * @param string $connected_at
     * @return $this
     */
    public function setConnectedAt($connected_at)
    {
        $this->connected_at = $connected_at;

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
     * Returns the value of field login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Returns the value of field firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Returns the value of field lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field connected_at
     *
     * @return string
     */
    public function getConnectedAt()
    {
        return $this->connected_at;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('main_db');
        $this->setSchema("public");
        $this->setSource("user");

        $this->hasMany('id', UserRole::class, 'id_user', ['alias' => 'UserRole']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|ResultSetInterface
     */
    public static function find($parameters = null): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User|ResultInterface
     */
    public static function findFirst($parameters = null): ?ModelInterface
    {
        return parent::findFirst($parameters);
    }

    /**
     *  Generate hash of application_slug
     */
    public function beforeCreate()
    {
        $this->setCreatedAt(date('Y-m-d H:i:s'));
    }

    /**********************************************************
     *
     *                         FUNCTIONS
     *
     **********************************************************/

    /**
     * @param string $login
     * @param string $password
     *
     * @return User
     */
    public static function checkConnexion(string $login, string $password)
    {
        $security = new Security();

        $user = self::findFirst([
            'conditions' => 'login = ?0',
            'bind' => [ $login ]
        ]);

        if ($user && $security->checkHash($password, $user->getPassword())) {
            $user
                ->setConnectedAt(date('Y-m-d H:i:s'))
                ->save();
        }

        return $user;
    }

}
