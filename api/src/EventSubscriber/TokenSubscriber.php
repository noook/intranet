<?php

namespace App\EventSubscriber;

use App\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

use App\Service\TokenHandler;
use App\Repository\TokenRepository;
use App\Security\TokenAuthenticator;
use App\Security\UserProvider;

class TokenSubscriber implements EventSubscriberInterface
{
    private $tokenHandler;
    private $tokenAuthenticator;
    private $userProvider;

    public function __construct(TokenHandler $tokenHandler, TokenRepository $tokenRepository, TokenAuthenticator $tokenAuthenticator, UserProvider $userProvider)
    {
        $this->tokenHandler = $tokenHandler;
        $this->tokenAuthenticator = $tokenAuthenticator;
        $this->userProvider = $userProvider;
        $this->tokenRepository = $tokenRepository;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $token = $request->headers->get('X-AUTH-TOKEN');

        if ('' == $token) {
            return;
        }

        $token = $this->tokenRepository->findOneBy(['token' => $token]);
        $user = $token->getOwner();

        if (is_null($user)) {
            throw new AccessDeniedHttpException('Requires Authentication');
        }
        
        $now = new \DateTime();
        $nearExpiracy = $token->getExpiracy()->sub(new \DateInterval('PT1H'));

        if ($nearExpiracy < $now) {
            $this->tokenHandler->refreshToken($token);
            $response->headers->set('X-REFRESHED-TOKEN', $token->getToken());
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse',
        );
    }
}