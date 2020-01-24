<?php


namespace EduBoxBundle\EventListener;


use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DetermineLocaleListener
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $locales = ['en', 'ru', 'tm'];
        $locale = $request->get('_locale');
        if (in_array($locale, $locales)) {
            $request->getSession()->set('_locale', $locale);
        }
        $request->setLocale($request->getSession()->get('_locale'));
    }
}