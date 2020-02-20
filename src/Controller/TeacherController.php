<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Results;
use App\Api\TeacherApi;



class TeacherController extends AbstractController
{

    /**
     * @Route("/users/coefs", name="users_coef")
     */
    public function postcoef(TeacherApi $teacherApi)
    {

        $results = $this->getDoctrine()->getRepository(Results::class)->findBy(['criteria' => 1]);

        foreach ($results as $result) {
            $result->setCoeforal(0.77);
            $result->setCoefelearning(0.23);
            $manag = $this->getDoctrine()->getManager();
            $manag->persist($result);
        }

        $results = $this->getDoctrine()->getRepository(Results::class)->findBy(['criteria' => 2]);
        foreach ($results as $result) {
            $result->setCoeforal(0.11);
            $result->setCoefelearning(0.89);
            $manag = $this->getDoctrine()->getManager();
            $manag->persist($result);
        }


        $results = $this->getDoctrine()->getRepository(Results::class)->findBy(['criteria' => 3]);
        foreach ($results as $result) {
            $result->setCoeforal(0.48);
            $result->setCoefelearning(0.52);
            $manag = $this->getDoctrine()->getManager();
            $manag->persist($result);
        }


        $results = $this->getDoctrine()->getRepository(Results::class)->findBy(['criteria' => 4]);
        foreach ($results as $result) {
            $result->setCoeforal(0.66);
            $result->setCoefelearning(0.34);
            $manag = $this->getDoctrine()->getManager();
            $manag->persist($result);
        }

        $manag->flush();
        return $this->json([
            'success' => true,

        ]);

    }
}
