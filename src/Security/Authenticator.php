<?php
namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Authenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $router;
    private $session;

    public function __construct(EntityManagerInterface $em, RouterInterface $router, SessionInterface $session)
    {
        $this->em = $em;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): bool
    {
        return  true;// $request->headers->has('X-AUTH-TOKEN');
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        $email = $request->server->get("SSL_CLIENT_S_DN_Email"); 

        if (!$email){
            return false;
        }

        return $email;
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {        
        if (null === $credentials || $credentials === false ) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            return null;
        }  
        
        

        $user = $userProvider->loadUserByUsername($credentials);     
        
        /* si no existe lo creamos */
        if(!$user->getEMail()){             

            $user= new User();
            $user->setEmail($credentials);
            $user->setRoles(['ROLE_USER']);

            $this->em->persist($user);
            $this->em->flush();           

        }
        
        // If this returns a user, checkCredentials() is called next:
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        
        // Check credentials - e.g. make sure the password is valid.
        // In case of an API token, no credential check is needed.

        // Return `true` to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {       
        
        $this->session->getFlashBag()->add('error', strtr($exception->getMessageKey(), $exception->getMessageData()));        
        return new RedirectResponse($this->router->generate('landing'));
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {       
            
        $this->session->getFlashBag()->add('error', 'Authentication Required ');

        return new RedirectResponse($this->router->generate('landing'));
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
