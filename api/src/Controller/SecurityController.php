<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Security\TokenAuthenticator;
use App\Entity\User;
use App\Service\TokenHandler;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
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

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenHandler $tokenHandler, ObjectManager $em)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $errors = [];
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        
        $checkEmail = $userRepository->findOneBy([
            'email' => $data['email'],
        ]);

        if (!is_null($checkEmail)) {
            $errors['email'] = true;
        }

        if ($data['password'] != $data['passwordConfirmation']) {
            $errors['password'] = true;
        }
        if (count($errors) > 0) {
            return $this->json([
                'errors' => $errors,
            ], 400);
        }

        $user = new User();

        $user
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email'])
            ->setPassword($passwordEncoder->encodePassword(
                $user,
                $data['password']
            ));

        $em->persist($user);
        $em->flush();

        $token = $tokenHandler->new($user);

        return $this->json([
            'token' => $token->getToken(),
        ]);
    }

    /**
     * @Route("/user/check-connection", name="check-connection", methods={"GET"})
     */
    public function checkConnection(Request $request, TokenAuthenticator $authenticator)
    {
        $user = $this->getUser();

        if (is_null($user)) {
            throw new AccessDeniedHttpException('Invalid token');
        }

        return $this->json([
            'user' => [
                'firstname' => $user->getFirstName(),
                'lastname' => $user->getLastName(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
        ]);
    }
}
