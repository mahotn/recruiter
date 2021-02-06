<?php

namespace App\Controller;

use App\Entity\Questionnaire;
use App\Form\QuestionnaireType;
use App\Repository\QuestionnaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionnaireController extends AbstractController
{
//    /**
//     * @Route("/questionnaire", name="questionnaire")
//     */
//    public function index(QuestionnaireRepository $questionnaireRepository): Response
//    {
//        $user = $this->getUser();
//        $questionnaires = $questionnaireRepository->findBy(['owner' => $user->getId()]);
//        return $this->render('home/questionnaires.html.twig', [
//            'QuestionnairesList' => $questionnaires,
//        ]);
//    }

    /**
     * @Route("/questionnaire/new", name="newQuestionnaire")
     * @return Response
     */
    public function newQuestionnaire(EntityManagerInterface $em, Request $request): Response
    {
        $user= $this->getUser();

        $questionnaire = new Questionnaire();
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // On définit l'utilisateur ayant crée le questionnaire comme propriétaire.
            $questionnaire->setOwner($user);
            // On associe les questions et le questionnaire.

            $em->persist($questionnaire);
            $em->flush();
            $this->addFlash('success', 'Votre questionnaire a bien été enregistré');
            return $this->redirectToRoute('questionnaire');
            // TO DO TRAITEMENT FORMULAIRE
        } else {
            return $this->render('questionnaire/newQuestionnaire.html.twig', [
                'newQuestionnaireForm' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/questionnaire/details", name="ajaxGetQuestionnaire")
     */
    public function ajaxGetQuestionnaire(QuestionnaireRepository $questionnaireRepository, Request $request): Response
    {
        if($request->isXmlHttpRequest()) {
            // On récupère l'id envoyé en Ajax et on l'utilise pour obtenir la fiche de poste demandée.
            $questionnaireId = $request->request->get('id');
            $questionnaire = $questionnaireRepository->find($questionnaireId);
            $jsonData = [];

            // Si une fiche de poste est bien renvoyée, on met en forme les données avant de les envoyer au front.
            if(empty($questionnaire)) {
                $jsonData['error'] = 'Fiche vide';
            } else {
                // On récupère chacune des missions et des compétences de la fiche.
                $questions= [];

                // Missions
                foreach ($questionnaire->getQuestions() as $question) {
                    $questions[] = $question->getLibelle();
                }


                array_push($jsonData, [
                    'name' => $questionnaire->getName(),
                    'questions' => $questions,
                ]);
            }

            return new JsonResponse($jsonData);
        }
    }
}
