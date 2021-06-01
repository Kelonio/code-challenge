<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\CertificateSerial;

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
        $entityManager = $this->getDoctrine()->getManager();

        $cert_serial = $request->server->get("SSL_CLIENT_M_SERIAL");
        $email = $request->server->get("SSL_CLIENT_S_DN_Email");
        $cn = $request->server->get("SSL_CLIENT_S_DN_CN");

        $user = $this->getUser();
        $serials = $user->getCertificateSerials();

        // buscamos con una funcion lambda
        $userSerial = $serials->filter(function(CertificateSerial $s) use ($cert_serial){
            return $s->getSerial() == $cert_serial;
        });
        
        // es un array , si los serial son unicos deberia de haber un solo elemento
        if(count($userSerial)==0){
           $newSerial =  new CertificateSerial();
           $newSerial->setSerial($cert_serial);
           $newSerial->setVisit(1);
           $newSerial->setUser($user);
           $entityManager->persist($newSerial);
           
        }else{            
            $userSerial[0]->setVisit($userSerial[0]->getVisit() + 1);
        }

        
        $entityManager->flush();


        return $this->render('home/index.html.twig', [            
            'cert_serial' => $cert_serial,
            'email'=> $email,
            'cn'=>$cn,
            'serials' => $user->getCertificateSerials()
        ]);
    }
}
