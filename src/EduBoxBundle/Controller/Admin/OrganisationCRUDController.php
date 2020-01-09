<?php


namespace EduBoxBundle\Controller\Admin;

use EduBoxBundle\Entity\Setting;
use EduBoxBundle\Form\Type\OrganisationType;
use Sonata\AdminBundle\Controller\CRUDController;

class OrganisationCRUDController extends CRUDController
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $respository = $em->getRepository(Setting::class);
        $settingManager = $this->get('edubox.setting_manager');
        $names = array(
            'shortName',
            'fullName',
            'address',
            'phone',
            'email',
            'director',
        );
        $settings = [];
        foreach ($names as $name) {
            $settings[$name] = $settingManager->getSetting($name);
        }

        $form = $this->createForm(OrganisationType::class, null, ['userManager' => $this->get('edubox.user_manager')]);

        foreach ($settings as $key => $value)
        {
            $form->get($key)->setData($value->getSettingValue());
        }

        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($settings as $key => $value)
            {
                $value->setSettingValue($form->get($key)->getData());
            }
            $em->flush();
            $this->addFlash('success', 'Settings saved successfully');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:organisation/edit.html.twig', [
            'org_form' => $form->createView(),
        ]);
    }
}