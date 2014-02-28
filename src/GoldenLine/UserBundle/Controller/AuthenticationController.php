<?php

namespace GoldenLine\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthenticationController extends Controller
{
    public function loginAction()
    {
        if(true === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('root'));
        }

        return $this->render('UserBundle:Authentication:login.html.twig');
    }
}
