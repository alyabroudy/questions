<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionGroup;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends AbstractController
{
    /**
     * @Route("/questions", name="questions_page")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $questionGroups = $em->getRepository(QuestionGroup::class)->findAll();

        return $this->render('questions/index.html.twig', [
            'questionGroups' => $questionGroups,
        ]);
    }

    /**
     * @Route("/questions/show/{id}", name="questions_show")
     */
    public function show($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var QuestionGroup $questionGroup */
        $currentQues = $em->getRepository(Question::class)->find($id);

        return $this->render('questions/show.html.twig', [
            'currentQues' => $currentQues,
        ]);
    }

    /**
     * @Route("/questions/create", name="questions_create")
     */
    public function create(Request $request)
    {
        $ques = new Question();

        $form = $this->createForm(QuestionType::class, $ques);

//        $em = $this->getDoctrine()->getManager();
        /** @var QuestionGroup $questionGroup */
  //      $currentQues = $em->getRepository(Question::class)->find(1);

        return $this->render('questions/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
