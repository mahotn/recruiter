<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Repository\JobDescriptionRepository;
use App\Repository\ProjectRepository;
use App\Repository\QuestionnaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruitementProjectController extends AbstractController
{
    /**
     * @Route("/recruitement/project/new", name="createRecruitementProject")
     */
    public function index(JobDescriptionRepository $jobDescriptionRepository, QuestionnaireRepository $questionnaireRepository, Request $request): Response
    {
        // On récupère la liste des fiches de poste pour les envoyer au front.
        $jobDescriptions = $jobDescriptionRepository->findBy(['owner' => $this->getUser()->getId()]);

        // On récupère la liste des questionnaires pour les envoyer au front.
        $questionnaires = $questionnaireRepository->findBy(['owner' => $this->getUser()->getId()]);

        // On crée un formulaire basé sur l'entité Project.
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        // Si la route est appelée par la requête ajax.
        if ($request->isXmlHttpRequest()) {
            // TO DO TRAITEMENT
        } else {
            return $this->render('recruitement_project/index.html.twig', [
                'jobDescriptionList' => $jobDescriptions,
                'questionnairesList' => $questionnaires,
                'newProjectForm' => $form->createView()
            ]);
        }
    }

    /**
     * Réception de la requête ajax POST pour la création d'un nouveau projet de recrutement.
     * @Route("/recruitement/project/create", name="ajaxCreateProject")
     */
    public function ajaxCreateProject(EntityManagerInterface $em, UserRepository $userRepository, JobDescriptionRepository $jobDescriptionRepository, QuestionnaireRepository $questionnaireRepository, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            // On récupère les informations envoyées par le front.
            $jobDescriptionId = $request->request->get('jobDescriptionId');
            $projectDetails = $request->request->get('details');
            $questionnaireId = $request->request->get('survey');

            // On crée une nouvelle instance de projet.
            $newProject = new Project();

            // On récupère l'utilisateur depuis la base afin de le passer au setter du projet.
            $user = $this->getUser();
            $currentUser = $userRepository->find($user->getId());

            // On associe le projet à l'utilisateur qui l'a créé.
            $newProject->setOwner($currentUser);

            // On récupère de la bdd la fiche métier choisie par l'utilisateur puis on l'associe au projet.
            $jobDescription = $jobDescriptionRepository->find($jobDescriptionId);
            $newProject->setJobDescription($jobDescription);

            // On récupère de la bdd le questionnaire choisi par l'utilisateur puis on l'associe au projet.
            $questionnaire = $questionnaireRepository->find($questionnaireId);
            $newProject->setQuestionnaire($questionnaire);

            // On enregistre tous les détails du projet.
            foreach ($projectDetails as $projectDetail) {
                // On reconvertit le tableau en objet afin d'accéder aux propriétés.
                $projectObject = json_decode(json_encode($projectDetail), false);
                // On découpe le nom de l'attribut qui est renvoyé sous la forme "project[attribut]" afin d'isoler le nom de l'attribut.
                $setterName = substr($projectObject->name, 8, -1);

                // En fonction du nom de l'attribut, on appelle le setter approprié.
                switch($setterName) {
                    case 'name':
                        $newProject->setName($projectObject->value);
                        break;
                    case 'contract':
                        $newProject->setContract($projectObject->value);
                        break;
                    case 'educationLevel':
                        $newProject->setEducationLevel($projectObject->value);
                        break;
                    case 'teamSize':
                        $newProject->setTeamSize($projectObject->value);
                        break;
                    case 'globalExperienceLevel':
                        $newProject->setGlobalExperienceLevel($projectObject->value);
                        break;
                    case 'experienceLevelAtPosition':
                        $newProject->setExperienceLevelAtPosition($projectObject->value);
                        break;
                    case 'managerJobTitle':
                        $newProject->setManagerJobTitle($projectObject->value);
                        break;
                    default:
                        break;
                }
            }

            $uniqueLink = $newProject->generateUniqueLink($newProject->getName());
            $newProject->setUniqueLink($uniqueLink);

            // On persiste et on flush.
            $em->persist($newProject);
            $em->flush();

            $jsonData[] = "Votre projet a bien été enregistré";

            return new JsonResponse($jsonData);
        }
    }
}
