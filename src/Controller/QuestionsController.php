<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionGroup;
use App\Entity\Scenario;
use App\Form\QuestionType;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
    public function show(QuestionGroup $questionGroup)
    {
      /*  dd($questionGroup);
        $em = $this->getDoctrine()->getManager();
        /** @var QuestionGroup $questionGroup */
       // $currentQues = $em->getRepository(Question::class)->find($id);

        return $this->render('questions/show.html.twig', [
            'questionGroup' => $questionGroup,
        ]);
    }

    /**
     * @Route("/questions/questionsgroup/{id}", name="questionsgroup_get")
     */
    public function getScenario(QuestionGroup $questionGroup)
    {
        /*  dd($questionGroup);
          $em = $this->getDoctrine()->getManager();
          /** @var QuestionGroup $questionGroup */
        // $currentQues = $em->getRepository(Question::class)->find($id);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        //$normalizers = [new ObjectNormalizer()];
        //$serializer = new Serializer($normalizers, $encoders);
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = [new ObjectNormalizer($classMetadataFactory)];
        $serializer = new Serializer($normalizer, $encoders);

        $jsonQG=  $serializer->serialize($questionGroup, 'json', [AbstractNormalizer::ATTRIBUTES=>[
            'id', 'name', 'description', 'scenarios'=>['id', 'title','description','points',
            'questions' =>['id', 'title', 'points','currectAnswer', 'answerDescription', 'isMultipleChoice',
                'answers'=>['id', 'title', 'description', 'isCorrect']]],
        ]]) ;

        return new JsonResponse($jsonQG);
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
