<?php

namespace App\Controller;

use App\Entity\JobDescription;
use App\Entity\Project;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\QuestionnaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MainController extends AbstractController
{
    /**
     * Racine du site. La route redirige l'utilisateur vers la bonne page en fonction de son statut connecté/déconnecté et de son rôle dans l'application.
     * @Route("/", name="index")
     */
    public function index(): Response
    {

        if($this->isGranted('ROLE_USER_VALIDATED') || $this->isGranted('ROLE_USER_RESTRICTED_ACCESS')) {
            // TO DO : rediriger vers la page d'accueil connecté du site.
            return $this->render('home/index.html.twig');
        } else if(!$this->isGranted('ROLE_USER_RESTRICTED_ACCESS') && $this->isGranted('ROLE_USER')) {
            // Si l'utilisteur est connecté mais qu'il n'a pas complété le formulaire lié à la première connexion.
            return $this->redirectToRoute('firstConnexion');
        } else {
            // Si l'utilisateur n'est pas connecté.
            return $this->render('main/index.html.twig');
        }

    }

    /**
     * Route déclenchée lors de la première connexion de l'utilisateur.
     * Elle renvoie vers le formulaire de complétion du profil.
     * @Route("/completeProfile", name="firstConnexion")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function firstConnection(EntityManagerInterface $em, Request $request) {

        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($userId);
        $formFirstConnection = $this->createForm(UserType::class, $user);

        $formFirstConnection->handleRequest($request);

        if($formFirstConnection->isSubmitted() && $formFirstConnection->isValid()) {
            // Récupération de l'image utilisateur.
            $profilePicture = $formFirstConnection->get('picture')->getData();
            if($profilePicture) {
                $file = md5(uniqid()) . '.' . $profilePicture->guessExtension();
                $profilePicture->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                $user->setPicture($file);
            }

            // L'utilisateur a bien rempli le questionnaire, on passe donc le boolean de validation d'account à 1.
            $user->setValidatedAccount(1);

            // Si l'utilisateur a bien rempli son profil et qu'il a validé son compte, on lui accord le ROLE_USER_VALIDATED. S'il n'a pas encore vérifié son compte, on lui accord un rôle provisoire.
            if($user->getValidatedAccount() && $user->isVerified()) {
                $roles = $user->getRoles();
                array_push($roles, 'ROLE_USER_VALIDATED');
                $user->setRoles($roles);
            } else if($user->getValidatedAccount() && !$user->isVerified()) {
                $roles = $user->getRoles();
                array_push($roles, 'ROLE_USER_RESTRICTED_ACCESS');
                $user->setRoles($roles);
            }

            // Enregistrement des données de l'utilisateur.
            $em->flush();

            // Evite à l'utilisateur d'être déconnecté lors du changement de rôle.
            $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);

            return $this->redirectToRoute('index');
        } else {
            return $this->render('home/firstConnection.html.twig', [
                'formFirstConnection' => $formFirstConnection->createView()
            ]);
        }
    }

    /**
     * Redirige vers la liste des fiches de postes.
     * @Route("/jobDescriptions", name="jobDescriptions")
     */
    public function jobDescriptions(EntityManagerInterface $em) {
        // On récupère toutes les fiches de postes de l'utilisateur courant.
        $user = $this->getUser();
        $jobDescriptions = $em->getRepository(JobDescription::class)->findBy(
            ['owner' => $user->getId()],
            ['jobTitle' => 'ASC']
        );

        return $this->render("/home/jobDescriptions.html.twig", [
            'jobDescriptionsList' => $jobDescriptions
        ]);
    }

    /**
     * Redirige vers la liste des projets de recrutement.
     * @Route("/recruitmentProjects", name="recruitmentProjects")
     */
    public function recruitmentProjects(EntityManagerInterface $em) {
        $user = $this->getUser();
        $recruitementProjects = $em->getRepository(Project::class)->findBy(
            ['owner' => $user->getId()],
            ['name' => 'ASC']
        );

        return $this->render("/home/recruitmentProjects.html.twig", [
            'recruitmentProjects' => $recruitementProjects
        ]);
    }

    /**
     * @Route("/questionnaire", name="questionnaire")
     */
    public function questionnaires(QuestionnaireRepository $questionnaireRepository): Response
    {
        $user = $this->getUser();
        $questionnaires = $questionnaireRepository->findBy(['owner' => $user->getId()]);
        return $this->render('home/questionnaires.html.twig', [
            'QuestionnairesList' => $questionnaires,
        ]);
    }
}
