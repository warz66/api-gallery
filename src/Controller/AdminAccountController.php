<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\UpdatePassword;
use App\Form\UpdatePasswordType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LoginFailedAttemptRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils, request $request, LoginFailedAttemptRepository $repo): Response
    {
        $delayRetryLogin = 15; // délai d'attente aprés x echecs de connexion
        $nbAttemptError = 5; // limite d'echecs de connexion 

        // on nettoie la table login_failed_attempt des anciennes echecs de connexion supérieure au délai d'attente
        $repo->cleanLoginFailedAttempts($delayRetryLogin);

        // on vérifie que le nombre d'échecs n'a pas atteint la limite prédéfinie
        if ($repo->countRecentLoginFailedAttempts($request->getClientIp(), $delayRetryLogin)>=$nbAttemptError) {
            return $this->render('admin/account/exceeded_failed_attempts.html.twig');
        }

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'error' => $error,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/admin/logout", name="admin_account_logout")
     * 
     * @return void
     */
    public function logout() {
        //...
    }

        /**
     * Permet de modifier son profil
     * @Route("/admin/account/profile", name="admin_account_profile")
     */
    public function profil(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {

        $UpdatePassword = new UpdatePassword();
        $formPassword = $this->createForm(UpdatePasswordType::class, $UpdatePassword);

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('role')->remove('informations');

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($user);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "Vos informations personnelles ont bien été modifiées !"
                );
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, vos informations personnelles n'ont pu être sauvegardées, vérifier les messages d'erreurs !"
                );
            }
        }

        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted()) {
            if ($formPassword->isValid()) {
                
                if($encoder->isPasswordValid($user, $UpdatePassword->getOldPassword())) {

                    $newPassword = $UpdatePassword->getNewPassword();
                    $hash = $encoder->encodePassword($user, $newPassword);
    
                    $user->setPassword($hash);
    
                    $manager->persist($user);
                    $manager->flush();
    
                    $this->addFlash(
                        'success',
                        "Votre mot de passe a bien été modifié !"
                    );
                } else {
                    $formPassword->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
                    $this->addFlash(
                        'danger',
                        "Attention, votre mot de passe n'a pu être modifié, vérifier les messages d'erreurs !"
                    );
                }

            } else {
                $this->addFlash(
                    'danger',
                    "Attention, votre mot de passe n'a pu être modifié, vérifier les messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/account/profile.html.twig', [
            'form' => $form->createView(),
            'formPassword' => $formPassword->createView(),
            ]);
    }
}
