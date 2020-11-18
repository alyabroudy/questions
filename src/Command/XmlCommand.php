<?php

namespace App\Command;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionGroup;
use App\Entity\Scenario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class XmlCommand extends Command
{
    protected static $defaultName = 'xml:import';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import data from an xml file')
            ->addArgument('filePath', InputArgument::OPTIONAL, 'xml FilePath')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('filePath');
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $classMetadataFactory = new ClassMetadataFactory(new XmlFileLoader('public/ihk.xml'));
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        if ($filePath) {
            $io->note(sprintf('You passed an argument: %s', $filePath));
        }else{
            $filePath = 'public/ihk.xml';
        }

        if (file_exists('public/ihk.xml')) {

            $file = file_get_contents('public/ihk.xml');
            $xmlOrder = simplexml_load_string($file);
            $counter=1;

            //create  questionGroup
            $ihkGroup = new QuestionGroup('IHK');
            $ihkGroup->setDescription('IHK Abschluss Fragen');
            //get the source tree which start in our case with <scenarios>
            foreach ($xmlOrder->children() as $elm){
                //foreach scenario we create a new object
                $scenario = new Scenario();
                //loop over all available scenarios
                foreach ($elm->children() as $elm2){
                    //if we reach the questions property
                    if ($elm2->getName() === 'questions'){
                        //loop over all available questions
                        foreach ($elm2->children() as $elm3){
                            //for every question we create a new object
                            $question= new Question();
                            // loop over each property of a question
                            foreach ($elm3->children() as $elm4){
                               // if property is answers
                                if ($elm4->getName() === 'answers'){
                                    //loop over each answer
                                    foreach ($elm4->children() as $elm5) {
                                        //we create answer object for each available answer
                                        $answer = new Answer();
                                        //loop over each property of an answer
                                        foreach ($elm5->children() as $elm6) {
                                            $pName = 'set' . ucfirst($elm6->getName());
                                            $answer->{$pName}( strip_tags(trim($elm6)));
                                            //$answer->{$pName}(  str_replace(array("\n", "\r"), '', strip_tags(trim($elm6))));
                                        }
                                        //add answer to the question
                                        $this->entityManager->persist($answer);
                                        $question->addAnswer($answer);
                                    }
                                }else{ //add other properties to question
                                    $pName = 'set'.ucfirst($elm4->getName());
                                    $question->{$pName}( strip_tags(trim($elm4)));
                                }
                            }
                            //add question to scenario
                            $this->entityManager->persist($question);
                            $scenario->addQuestion($question);
                        }
                    }else{
                        //add other properties to scenario
                        $pName = 'set'.ucfirst($elm2->getName());
                        $scenario->{$pName}(  strip_tags(trim($elm2)));
                    }
                }
                $scenario->setQuestionGroup($ihkGroup);
                $this->entityManager->persist($scenario);
                $counter++;
            }
            $this->entityManager->persist($ihkGroup);
            $this->entityManager->flush();
            $io->success($counter.' xml Data imported successfully!');
        } else {
            exit('Failed to open test.xml.');
        }
      /*  if ($input->getOption('option1')) {
            // ...
        } */
       // $io->success('Data imported successfully!');
        return 0;
    }
}
