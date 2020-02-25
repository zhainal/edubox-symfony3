<?php


namespace EduBoxBundle\EventListener;


use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class DetermineLocaleListener
{
    public static $locales = ['en', 'ru', 'tm'];

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $locale = $request->get('_locale');
        if (in_array($locale, self::$locales)) {
            $request->getSession()->set('_locale', $locale);
        }
        $request->setLocale($request->getSession()->get('_locale'));
    }
}