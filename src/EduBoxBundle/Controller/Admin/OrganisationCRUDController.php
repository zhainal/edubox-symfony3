<?php


namespace EduBoxBundle\Controller\Admin;

use EduBoxBundle\Entity\Setting;
use EduBoxBundle\Form\Type\OrganisationType;
use Sonata\AdminBundle\Controller\CRUDController;

class OrganisationCRUDController extends CRUDController
{
    public function listAction()
    {
        $this->admin->checkAccess('list');
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
            'smsEnabled',
            'smsApiId',
            'smsBalance',
        );
        $settings = [];
        $data = [];
        foreach ($names as $name) {
            $settings[$name] = $settingManager->getSetting($name);
            $data[$name] = $settings[$name]->getSettingValue();
        }

        $form = $this->createForm(OrganisationType::class, $data, ['userManager' => $this->get('edubox.user_manager')]);

        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($settings as $key => $value)
            {
                $value->setSettingValue($form->get($key)->getData());
            }
            $em->flush();
            $this->addFlash('success', $this->trans('organisation_saved',[],'EduBoxBundle'));
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:organisation/edit.html.twig', [
            'org_form' => $form->createView(),
        ]);
    }
}