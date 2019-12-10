<?php


namespace EduBoxBundle\Controller\Admin;

use EduBoxBundle\Entity\Setting;
use EduBoxBundle\Form\OrganisationType;
use Sonata\AdminBundle\Controller\CRUDController;

class OrganisationCRUDController extends CRUDController
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $respository = $em->getRepository(Setting::class);


        $data = array(
            'shortName' => $respository->findOneByOrCreate(['settingKey'=>'short_name']),
            'fullName' => $respository->findOneByOrCreate(['settingKey'=>'full_name']),
            'address' => $respository->findOneByOrCreate(['settingKey'=>'address']),
            'phone' => $respository->findOneByOrCreate(['settingKey'=>'phone']),
            'email' => $respository->findOneByOrCreate(['settingKey'=>'email']),
            'director' => $respository->findOneByOrCreate(['settingKey'=>'director']),
        );

        $form = $this->createForm(OrganisationType::class);

        foreach ($data as $key => $value)
        {
            $form->get($key)->setData($value->getSettingValue());
        }

        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($data as $key => $value)
            {
                $value->setSettingValue($form->get($key)->getData());
            }
            $em->flush();
            $this->addFlash('success', 'Settings saved successfully');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:organisation_edit.html.twig', [
            'org_form' => $form->createView(),
        ]);
    }
}