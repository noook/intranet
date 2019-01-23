<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Grade;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GradeController extends AbstractController
{
    /**
     * @Route("/grade/{id}", name="grade-detail", methods={"GET"})
     * @ParamConverter("grade", class="App\Entity\Grade")
     * @IsGranted("ROLE_STUDENT")
     */
    public function gradeDetail(Grade $grade)
    {
        $user = $this->getUser();

        $isOwner = $user->getGrades()->contains($grade);
        $isTeacher = $user->getClasses()->contains($grade->getCourse());

        if (!$isOwner && !$isTeacher) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        return $this->json([
            'grade' => $grade->repr(),
        ]);
    }

    /**
     * @Route("/admin/grade/{id}/edit", name="admin-grade-edit", methods={"PUT"})
     * @ParamConverter("grade", class="App\Entity\Grade")
     * @IsGranted("ROLE_ADMIN")
     */
    public function gradeEdition(Grade $grade, Request $request, ObjectManager $em)
    {
        $data = json_decode($request->getContent(), true);
        dump($data);

        $grade
            ->setValue($data['value'])
            ->setComment($data['comment']);

        $em->flush();
        
        return $this->json([
            'grade' => $grade->repr(),
        ]); 
    }
}
