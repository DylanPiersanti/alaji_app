<?php

namespace App\Controller;

use App\Entity\Quizzes;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz", name="quiz_list")
     */
    public function getQuizzes()
    {
        $user = $this->getUser();

        $quizzes = $this->getDoctrine()->getRepository(Quizzes::class)->findBy(
            ['user' => $user]
        );
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }
}
