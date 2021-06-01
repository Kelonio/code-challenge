<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/secured/home", name="home")
     */
    public function index(Request $request): Response
    {
        /*
        SSL_CLIENT_CERT_RFC4523_CEA
        SSL_CLIENT_S_DN
        */

      
        $cert_serial = $request->server->get("SSL_CLIENT_M_SERIAL");
        $email = $request->server->get("SSL_CLIENT_S_DN_Email");
        $cn = $request->server->get("SSL_CLIENT_S_DN_CN");

        return $this->render('home/index.html.twig', [            
            'cert_serial' => $cert_serial,
            'email'=> $email,
            'cn'=>$cn
        ]);
    }
}
