<?php

namespace App\Controller;

use App\Entity\Students;
use App\Api\TeacherApi;
use App\Entity\Results;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


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
    public function getCandidate(int $id, Request $request, TeacherApi $teacherApi)
    {

        $student = $this->getDoctrine()->getRepository(Students::class)->find($id);

        if ($request->isMethod('POST')) {
            $submittedToken = $request->request->get('token');
            if ($this->isCsrfTokenValid('addCriteria', $submittedToken)) {
                $oral1 = intval($_POST['criteria1']);
                $oral2 = intval($_POST['criteria2']);
                $oral3 = intval($_POST['criteria3']);
                $oral4 = intval($_POST['criteria4']);

                $results = $this->getDoctrine()->getRepository(Results::class)->findOneBy(['student' => $student, 'criteria' => 1]);
                $results->setOral($oral1);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($results);


                $results = $this->getDoctrine()->getRepository(Results::class)->findOneBy(['student' => $student, 'criteria' => 2]);
                $results->setOral($oral2);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($results);


                $results = $this->getDoctrine()->getRepository(Results::class)->findOneBy(['student' => $student, 'criteria' => 3]);
                $results->setOral($oral3);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($results);


                $results = $this->getDoctrine()->getRepository(Results::class)->findOneBy(['student' => $student, 'criteria' => 4]);
                $results->setOral($oral4);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($results);

                $manager->flush();

            }
            $results = $this->getDoctrine()->getRepository(Results::class)->findBy(['student' => $student]);
            
        }


        $results = $this->getDoctrine()->getRepository(Results::class)->findBy(['student' => $student]);


        return $this->render('students/detail.html.twig', [
            'student' => $student,
            'results' => $results
        ]);
    }

    /**
     * @Route("/student/{id}/addoral", name="student_form")
     */
    public function getFormCandidate(int $id, Request $request)
    {
        $student = $this->getDoctrine()->getRepository(Students::class)->find($id);
        $results = $this->getDoctrine()->getRepository(Results::class)->findBy(['student' => $student]);

        return $this->render('oral/addoral.html.twig', [
            'student' => $student,
            'results' => $results
        ]);
    }
}
