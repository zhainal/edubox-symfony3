<?php

namespace EduBoxBundle\Repository;

use EduBoxBundle\Entity\Setting;

/**
 * SettingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SettingRepository extends \Doctrine\ORM\EntityRepository
{
    public function findOneByOrCreate(array $criteria)
    {
        $em = $this->getEntityManager();
        $setting = $this->findOneBy($criteria);

        if ($setting === null) {
            $setting = new Setting();
            foreach ($criteria as $key => $value) {
                $setter = 'set'.ucfirst($key);
                $setting->$setter($value);
            }
            $em->persist($setting);
            $em->flush();
        }

        return $setting;
    }
}
