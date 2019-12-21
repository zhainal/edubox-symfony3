<?php


namespace EduBoxBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class User
 * @package EduBoxBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="box_users")
 */
class User extends BaseUser
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=32, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=32, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(name="middle_name", type="string", length=32, nullable=true)
     */
    protected $middleName;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=32, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="gender", type="integer", length=1, nullable=true)
     */
    protected $gender;

    /**
     * @var string
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    protected $birthday;

    /**
     * @var UserMeta
     *
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\UserMeta", mappedBy="user")
     */
    protected $userMeta;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\Subject", mappedBy="user")
     */
    protected $subjects;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     * @return $this|BaseUser|\FOS\UserBundle\Model\UserInterface
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param $firstName
     * @return $this|BaseUser|\FOS\UserBundle\Model\UserInterface
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $lastName
     * @return $this|BaseUser|\FOS\UserBundle\Model\UserInterface
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param $middleName
     * @return $this|BaseUser|\FOS\UserBundle\Model\UserInterface
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->middleName . ' ' . $this->lastName;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     * @return $this|BaseUser|\FOS\UserBundle\Model\UserInterface
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param $gender
     * @return $this|BaseUser|\FOS\UserBundle\Model\UserInterface
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param $birthday
     * @return $this|BaseUser|\FOS\UserBundle\Model\UserInterface
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }


    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function __toString()
    {
        return 'User';
    }
}