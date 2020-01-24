<?php


namespace EduBoxBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class User
 * @package EduBoxBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    const DEFAULT_IMG_PATH = 'user.jpg';
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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\Subject", mappedBy="user")
     */
    protected $subjects;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\Quarter", mappedBy="user")
     */
    protected $quarters;

    /**
     * @Assert\File(maxSize="2048k")
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $profilePictureFile;

    // for temporary storage
    private $tempProfilePicturePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $profilePicturePath;



    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        parent::__construct();

    }

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
        $full_name = trim($this->firstName . ' ' . $this->middleName . ' ' . $this->lastName);
        return $full_name == '' ? $this->getUsername() : $full_name;
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
     * @return mixed
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    public function getGenderAsString()
    {
        $gender = $this->getGender();
        if($gender === UserMeta::FEMALE) {
            return 'Female';
        } elseif($gender === UserMeta::MALE) {
            return 'Male';
        }
        return null;
    }

    public function getRolesWithoutRoleUser()
    {
        $roles = $this->getRoles();
        $index = @array_flip($roles)['ROLE_USER'];
        if (isset($roles[$index])) {
            unset($roles[$index]);
        }
        return $roles;
    }

    /**
     * Sets the file used for profile picture uploads
     *
     * @param UploadedFile $file
     * @return object
     */
    public function setProfilePictureFile(UploadedFile $file = null) {
        $this->profilePictureFile = $file;
        $this->preUploadProfilePicture();
        // check if we have an old image path
        if (isset($this->profilePicturePath)) {
            // store the old name to delete after the update
            $this->tempProfilePicturePath = $this->profilePicturePath;
            #$this->profilePicturePath = null;
        } else {
            $this->profilePicturePath = 'initial';
        }
        $this->uploadProfilePicture();

        return $this;
    }

    /**
     * Get the file used for profile picture uploads
     *
     * @return UploadedFile
     */
    public function getProfilePictureFile() {

        return $this->profilePictureFile;
    }

    /**
     * Set profilePicturePath
     *
     * @param string $profilePicturePath
     * @return User
     */
    public function setProfilePicturePath($profilePicturePath)
    {
        $this->profilePicturePath = $profilePicturePath;
        return $this;
    }

    /**
     * Get profilePicturePath
     *
     * @return string
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * Get the absolute path of the profilePicturePath
     */
    public function getProfilePictureAbsolutePath() {
        return null === $this->profilePicturePath
            ? null
            : $this->getUploadRootDir().'/'.$this->profilePicturePath;
    }

    /**
     * Get root directory for file uploads
     *
     * @return string
     */
    protected function getUploadRootDir($type = 'profilePicture') {
        // the absolute directory path where uploaded
        // documents should be saved
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        return $kernel->getProjectDir().'/web/'.$this->getUploadDir($type);
    }

    /**
     * Specifies where in the /web directory profile pic uploads are stored
     *
     * @return string
     */
    protected function getUploadDir($type = 'profilePicture') {
        // the type param is to change these methods at a later date for more file uploads
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'assets/img/user';
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebProfilePicturePath() {
        if (file_exists($this->getProfilePictureAbsolutePath())) {
            return '/'.$this->getUploadDir().'/'.$this->getProfilePicturePath();
        }
        return '/'.$this->getUploadDir().'/'.self::DEFAULT_IMG_PATH;
    }

    /**
     */
    public function preUploadProfilePicture() {
        if (null !== $this->getProfilePictureFile()) {
            // a file was uploaded
            // generate a unique filename
            $filename = 'img'.$this->getId();
            $this->setProfilePicturePath($filename.'.'.$this->getProfilePictureFile()->getClientOriginalExtension());
        }
    }


    /**
     */
    public function uploadProfilePicture() {
        // check there is a profile pic to upload
        if ($this->getProfilePictureFile() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getProfilePictureFile()->move($this->getUploadRootDir(), $this->getProfilePicturePath());

        $this->tempProfilePicturePath = null;
        $this->profilePictureFile = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeProfilePictureFile()
    {
        if ($file = $this->getProfilePictureAbsolutePath() && file_exists($this->getProfilePictureAbsolutePath())) {
            unlink($file);
        }
    }

    public function __toString()
    {
        return 'User';
    }
}