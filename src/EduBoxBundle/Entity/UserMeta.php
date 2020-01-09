<?php

namespace EduBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserMeta
 *
 * @ORM\Table(name="user_meta")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\UserMetaRepository")
 */
class UserMeta
{
    const MALE=1,FEMALE=2;

    const STUDENT_GROUP_ID = 'students_group_id';
    const STUDENT_PARENT_ID = 'parent_id';
    const PARENT_STUDENT_IDS = 'student_ids';

    const ROLE_STUDENT = array('STUDENT_GROUP_ID', 'STUDENT_PARENT_ID');
    const ROLE_PARENT = array('PARENT_STUDENT_IDS');

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_key", type="string", length=255)
     */
    private $metaKey;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_value", type="text", nullable=true)
     */
    private $metaValue;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\User", inversedBy="userMeta")
     */
    protected $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set metaKey
     *
     * @param string $metaKey
     *
     * @return UserMeta
     */
    public function setMetaKey($metaKey)
    {
        $this->metaKey = $metaKey;

        return $this;
    }

    /**
     * Get metaKey
     *
     * @return string
     */
    public function getMetaKey()
    {
        return $this->metaKey;
    }

    /**
     * Set metaValue
     *
     * @param string $metaValue
     *
     * @return UserMeta
     */
    public function setMetaValue($metaValue)
    {
        $this->metaValue = $metaValue;

        return $this;
    }

    /**
     * Get metaValue
     *
     * @return string
     */
    public function getMetaValue()
    {
        return $this->metaValue;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function __toString()
    {
        return 'UserMeta';
    }
}

