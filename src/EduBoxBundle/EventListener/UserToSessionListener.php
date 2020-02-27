<?php


namespace EduBoxBundle\EventListener;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\Entity\User;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserToSessionListener
{
    public static $themes = ['dark', 'light'];

    private $tokenStorage;
    private $entityManager;

    public function __construct(TokenStorage $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        // get new vars
        $locale = $request->get('_locale');
        $theme = $request->get('_theme');

        // if no new vars, get user vars
        if ($user instanceof User && $locale == '') {
            $locale = $user->getLocale();
        }
        if ($user instanceof User && $theme == '') {
            $theme = $user->getTheme();
        }

        // apply vars to session
        if (in_array($locale, DetermineLocaleListener::$locales)) {
            $request->getSession()->set('_locale', $locale);
            if ($user instanceof User) {
                if ($user->getLocale() != $locale) {
                    $user->setLocale($locale);
                }
            }
        }
        if (in_array($theme, self::$themes)) {
            $request->getSession()->set('_theme', $theme);
            if ($user instanceof User) {
                if ($user->getTheme() != $theme) {
                    $user->setTheme($theme);
                }
            }
        }
        $this->entityManager->flush();
    }
}