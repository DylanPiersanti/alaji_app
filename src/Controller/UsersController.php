<?php

namespace App\Controller;

use App\Api\TeacherApi;

use App\Entity\Users;
use App\Entity\Students;
use App\Entity\Quizzes;
use App\Entity\Criteria;
use App\Entity\Results;
use App\Repository\ResultsRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTime;


class UsersController extends AbstractController
{
    

    /**
     * @Route("/users/quiz", name="users_quiz")
     */
    public function getInfos(TeacherApi $teacherApi)
    {

        $user = $this->getUser();
        $userEmail = $user->getEmail();

        $teacher = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['email' => $userEmail]);

        $idTeacher = $teacher->getMoodleId();

        $courses = $teacherApi->getCourses($idTeacher);

        $idCourses = $courses['groups'][0]['courseid'];

        $quizzes = $teacherApi->getQuiz($idCourses);
        $nameQuiz = $quizzes["quizzes"][0]['name'];
        $idQuiz = $quizzes["quizzes"][0]['id'];

        $quizDb = $this->getDoctrine()->getRepository(Quizzes::class)->findOneBy(['moodle_id' => $idQuiz]);

        if (!$quizDb) {

            $quiz = new Quizzes;
            $quiz->setName($nameQuiz);
            $quiz->setUser($teacher);
            $quiz->setMoodleId($idQuiz);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($quiz);
            $manager->flush();

            return $this->json([
                'success' => true,
                'idQuiz' => $quiz->getId()
            ]);

        } else {
            throw new \Exception("Quiz déjà enregistré en base de donnée");
        }
    }

    /**
     * @Route("/users/students", name="users_students")
     */
    public function postCandidate(TeacherApi $teacherApi)
    {

        $user = $this->getUser();
        $userEmail = $user->getEmail();

        $teacher = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['email' => $userEmail]);

        $idTeacher = $user->getMoodleId();

        $courses = $teacherApi->getCourses($idTeacher);
        $idCourses = $courses['groups'][0]['courseid'];
        $idGroup = $courses['groups'][0]['id'];


        $users = $teacherApi->getUsers($idCourses);
        foreach ($users as $user) {

            $roles = $user['roles'][0]['roleid'];
            $group = $user['groups'];

            if (!empty($group)) {
                $groupIdCandidate = $group[0]['id'];
            }

            if ($roles === 5 && $groupIdCandidate === $idGroup) {
                $idCandidate = $user['id'];
                $fullnameCandidate = $user['fullname'];
                $avatarCandidate = $user['profileimageurl'];
                $emailCandidate = $user['email'];
                $firstacces = $user['firstaccess'];

                $candidateDb =  $this->getDoctrine()->getRepository(Students::class)->findOneBy(['moodle_id' => $idCandidate]);
                if (!$candidateDb) {

                    $candidate = new Students;

                    $candidate->setFullname($fullnameCandidate);
                    $candidate->setMoodleId($idCandidate);
                    $candidate->setEmail($emailCandidate);
                    $candidate->setUser($teacher);
                    $candidate->setAvatar($avatarCandidate);
                    $candidate->setFirstaccess($firstacces);

                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($candidate);
                    $manager->flush();

                }
            }

        }
        return $this->json([
            'success' => true,

        ]);


    }

    /**
     * @Route("/users/criteria", name="users_criteria")
     */
    public function postCriteria(TeacherApi $teacherApi)
    {

        $user = $this->getUser();
        $userEmail = $user->getEmail();

        $teacher = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['email' => $userEmail]);

        $idTeacher = $teacher->getMoodleId();

        $courses = $teacherApi->getCourses($idTeacher);
        $idCourses = $courses['groups'][0]['courseid'];

        $quizzes = $teacherApi->getQuiz($idCourses);
        $idQuiz = $quizzes["quizzes"][0]['id'];

        $candidateDb =  $this->getDoctrine()->getRepository(Students::class)->findOneBy(['user' => $teacher ]);
        $idMoodleCandidate = $candidateDb->getMoodleId();

        $attempt = $teacherApi->getAttempsUser($idQuiz, $idMoodleCandidate);
        $idAttempt = end($attempt['attempts'])['id'];

        $attemptreview = $teacherApi->getAttempsReview($idAttempt);
        $questions = $attemptreview['questions'];

        $quizCriteria =  $this->getDoctrine()->getRepository(Quizzes::class)->findOneBy(['moodle_id' => $idQuiz]);

        foreach ($questions as $question) {
            $nameQuestion = $teacherApi->getQuestionDescription($question['html']);


            $criteriaDb =  $this->getDoctrine()->getRepository(Criteria::class)->findOneBy(['name' => $nameQuestion]);

            if (!$criteriaDb) {

                $criteria = new Criteria;

                $criteria->setName($nameQuestion);
                $criteria->setQuiz($quizCriteria);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($criteria);
                $manager->flush();

            }
        }

        return $this->json([
            'success' => true,

        ]);

    }

    /**
     * @Route("/users/result", name="user_result")
     */
    public function postResult(TeacherApi $teacherApi)
    {

        $user = $this->getUser();
        $userEmail = $user->getEmail();

        $teacher = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['email' => $userEmail]);

        $idTeacher = $teacher->getMoodleId();

        $courses = $teacherApi->getCourses($idTeacher);
        $idCourses = $courses['groups'][0]['courseid'];

        $quizzes = $teacherApi->getQuiz($idCourses);
        $idQuiz = $quizzes["quizzes"][0]['id'];

        $dbCandidates =  $this->getDoctrine()->getRepository(Students::class)->findBy(['user' => $teacher ]);


        foreach ($dbCandidates as $dbCandidate) {

            $idMoodleCandidate = $dbCandidate->getMoodleId();
            $idCandidateDb = $dbCandidate->getId();


            $attempt = $teacherApi->getAttempsUser($idQuiz, $idMoodleCandidate);
            $idAttempt = end($attempt['attempts'])['id'];

            $attemptreview = $teacherApi->getAttempsReview($idAttempt);
            $questions = $attemptreview['questions'];

            foreach ($questions as $question) {

                $testNote = intval($question['mark']);
                $nameQuestion = $teacherApi->getQuestionDescription($question['html']);


                $nameCriteria =  $this->getDoctrine()->getRepository(Criteria::class)->findOneBy(['name' => $nameQuestion]);
                $idNameCriteria = $nameCriteria->getId();


                $testNoteDb =  $this->getDoctrine()->getRepository(Results::class)->findOneBy([
                    'elearning' => $testNote,
                    'student' => $idCandidateDb,
                    'criteria' => $idNameCriteria
                ]);

                if (!$testNoteDb) {

                    $result = new Results;

                    $result->setStudent($dbCandidate);
                    $result->setCriteria($nameCriteria);
                    $result->setElearning($testNote);
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($result);
                    $manager->flush();

                }
            }

        }
        return $this->json([
            'success' => true,

        ]);

    }
}
