<?php

namespace App\Controller;

use App\Entity\Students;
use App\Entity\Results;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StudentsController extends AbstractController
{
    /**
     * @Route("/students", name="students_list")
     */
    public function getAllCandidates()
    {
        $user = $this->getUser();

        $candidates = $this->getDoctrine()->getRepository(Students::class)->findBy(
            ['user' => $user]
        );

        return $this->render('students/index.html.twig', [
            'candidates' => $candidates,
        ]);
    }

    /**
     * @Route("/student/{id}", name="student_detail")
     */
    public function getCandidate(int $id)
    {

        $student = $this->getDoctrine()->getRepository(Students::class)->find($id);

        $results = $this->getDoctrine()->getRepository(Results::class)->findBy(['student' => $student]);


        return $this->render('students/detail.html.twig', [
            'student' => $student,
            'results' => $results
        ]);
    }
}
