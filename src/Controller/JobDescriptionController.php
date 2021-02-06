<?php

namespace App\Controller;

use App\Entity\JobDescription;
use App\Form\JobDescriptionType;
use App\Repository\JobDescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobDescriptionController extends AbstractController
{
    /**
     * @Route("/jobDescription/create", name="newJobDescription")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $jobDescription = new JobDescription();

        $form = $this->createForm(JobDescriptionType::class, $jobDescription);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // On associe la fiche de poste à l'utilisateur qui l'a créee.
            $user = $this->getUser();
            $user->addJobDescription($jobDescription);

            $em->persist($jobDescription);
            $em->flush();
            $this->addFlash('success', 'Votre fiche de poste a bien été créée.');
            return $this->redirectToRoute('jobDescriptions');
        } else {
            return $this->render('job_description/index.html.twig', [
                'newJobDescriptionForm' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/jobDescription/details/{id}", name="getJobDescriptionDetails")
     * @Route("/jobDescription/details", name="ajaxGetJobDescriptionDetails", methods={"POST"})
     */
    public function getJobDescriptionDetails(EntityManagerInterface $em, JobDescriptionRepository $jobDescriptionRepository, Request $request, $id = null) {
        // Traitement de la requête Ajax
        if($request->isXmlHttpRequest()) {
            // On récupère l'id envoyé en Ajax et on l'utilise pour obtenir la fiche de poste demandée.
            $ajaxId = $request->request->get('id');
            $jobDescription = $jobDescriptionRepository->find($ajaxId);
            $jsonData = [];

            // Si une fiche de poste est bien renvoyée, on met en forme les données avant de les envoyer au front.
            if(empty($jobDescription)) {
               $jsonData['error'] = 'Fiche vide';
            } else {
                // On récupère chacune des missions et des compétences de la fiche.
                $missions= [];
                $skills = [];

                // Missions
                foreach ($jobDescription->getMissions() as $mission) {
                    $missions[] = $mission->getLibelle();
                }

                // Compétences
                foreach ($jobDescription->getSkills() as $skill) {
                    $skills[] = $skill->getLibelle();
                }

                array_push($jsonData, [
                  'jobTitle' => $jobDescription->getJobTitle(),
                  'description' => $jobDescription->getDescription(),
                  'missions' => $missions,
                  'skills' => $skills,
                ]);
            }

            return new JsonResponse($jsonData);
        } else {
            // On récupère la fiche de poste.
            $jobDescription = $jobDescriptionRepository->find($id);

//        // TO DO On vérifie que la fiche de poste existe bien et que l'utilisateur est bien autorisé à la consulter.
//        if(empty($jobDescription)) {
//            // TO DO : retourner un message d'erreur.
//        } else {
//            // On vérifie que l'utilisateur a bien le droit d'accéder à la ressource.
//            if($jobDescription->ge)
//        }

            if(empty($jobDescription)) {
                $this->addFlash('danger', 'Il y a eu une erreur lors de la récupération de la fiche de poste.');
            }

            return $this->render('job_description/details.html.twig', [
                'jobDescription' => $jobDescription
            ]);
        }
    }

    /**
     * @Route("/jobDescription/edit/{id}", name="editJobDescription")
     */
    public function editJobDescription(EntityManagerInterface $em, JobDescriptionRepository $jobDescriptionRepository, Request $request, $id) {
        // On récupère la fiche de poste afin de la transmettre au formulaire.
        $jobDescription = $jobDescriptionRepository->find($id);
        $form = $this->createForm(JobDescriptionType::class, $jobDescription);

        $form->handleRequest($request);

        if(empty($jobDescription)) {
            $this->addFlash('danger', 'Il y a eu une erreur lors de la récupération de la fiche de poste.');
        }

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash("success", "La fiche de poste {$jobDescription->getJobTitle()} a bien été mise à jour.");
            return $this->redirectToRoute('jobDescriptions');
        } else {
            return $this->render('job_description/editJobDescription.html.twig', [
                'editJobDescriptionForm' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/jobDescription/delete/{id}", name="deleteJobDescription")
     */
    public function deleteJobDescription(EntityManagerInterface $em, JobDescriptionRepository $jobDescriptionRepository, $id) {
        $jobDescription = $jobDescriptionRepository->find($id);
        $em->remove($jobDescription);
        $em->flush();
        $this->addFlash("success", "La fiche de poste {$jobDescription->getJobTitle()} a bien été supprimé.");
        return $this->redirectToRoute('jobDescriptions');
    }
}
