<?php

namespace EduBoxBundle\Entity;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Setting
 *
 * @ORM\Table(name="setting")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\SettingRepository")
 */
class Setting
{
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
     * @ORM\Column(name="setting_key", type="string", length=255, unique=true)
     */
    private $settingKey;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_value", type="text", nullable=true)
     */
    private $settingValue;

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
     * Set settingKey
     *
     * @param string $settingKey
     *
     * @return Setting
     */
    public function setSettingKey($settingKey)
    {
        $this->settingKey = $settingKey;

        return $this;
    }

    /**
     * Get settingKey
     *
     * @return string
     */
    public function getSettingKey()
    {
        return $this->settingKey;
    }

    /**
     * Set settingValue
     *
     * @param string $settingValue
     *
     * @return Setting
     */
    public function setSettingValue($settingValue)
    {
        $this->settingValue = $settingValue;

        return $this;
    }

    /**
     * Get settingValue
     *
     * @return string
     */
    public function getSettingValue()
    {
        return $this->settingValue;
    }
}

