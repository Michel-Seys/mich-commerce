<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route ("/logout", name="security_logout")
     */
    public function logout()
    {

    }
}
