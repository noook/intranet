<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\TokenHandler;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenHandler $tokenHandler)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $data['email'],
        ]);

        if (is_null($user)) {
            throw new BadRequestHttpException('Invalid email or password');
        }

        if ($passwordEncoder->isPasswordValid($user, $data['password'])) {
            $token = $tokenHandler->new($user);
            return $this->json([
                'token' => $token->getToken(),
            ]);
        } else {
            throw new BadRequestHttpException('Invalid email or password');
        }
    }
}
