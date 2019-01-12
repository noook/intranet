<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Token;
use App\Entity\User;

class TokenHandler {
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    private function isAvailable($token): Bool
    {
        return !$this->em->getRepository(Token::class)->findOneBy([
            'token' => $token
        ]);
    }

    public function generate(): String
    {
        $newToken = bin2hex(random_bytes(50));
        if (!$this->isAvailable($newToken)) {
            $this->generate();
        }
        return $newToken;
    }

    public function new(User $user): Token
    {
        $token = new Token;
        $expiracy = (new \DateTime())->add(new \DateInterval('P1D'));
        $token
            ->setOwner($user)
            ->setToken($this->generate())
            ->setExpiracy($expiracy);

        $this->em->persist($token);
        $this->em->flush();

        return $token;
    }

    // public function refreshToken($token)
    // {
    //     $user = $this->em->getRepository(Token::class)->findOneBy([
    //         'token' => $token
    //     ]);
    //     if (is_null($user)) {
    //         return 401;
    //     }
    //     $newToken = $this->generate();
    //     $user->setApiToken($newToken);
    //     $now = new \DateTime();
    //     $user->setTokenExpiracy($now->add(new \DateInterval('P1D')));
    //     $this->em->flush();

    //     return $newToken;
    // }
}
