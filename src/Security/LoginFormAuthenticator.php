<?php

namespace App\Security;

use App\Entity\LoginFailedAttempt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $parameterBag;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->parameterBag = $parameterBag;
    }

    public function supports(Request $request)
    {   
        return 'admin_account_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {   
        
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
            'g-recaptcha-response' => $request->request->get('g-recaptcha-response'), // google recaptcha
            'username' => $request->request->get('username') , // honeypot
            'ipAddress' => $request->getClientIp()
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {   
        //on gere le pot de miel contre les bots
        if (!empty($credentials['username'])) {
            throw new CustomUserMessageAuthenticationException("it's a bot ?");
        }
        
        //on vérifie la réponse que google renvoie (protection anti spam recaptcha)
        $urlRecaptcha = "https://www.google.com/recaptcha/api/siteverify?secret={$this->parameterBag->get('google_recaptcha_v3_secret')}&response={$credentials['g-recaptcha-response']}";
        
        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $urlRecaptcha)->toArray();
            if(!$response['success']) {
                throw new \Exception;
            }
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException("Une erreur est survenue lors de la réponse google recaptcha, veuillez réessayer, si ce problème persiste, contacter l'administrateur.");
        }
        
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        // là on enregistre chaque echec de tentative de connection avec le login ou mot de passe invalide, pour réseau d'entreprise avec la même addresse ip, il serai préférable de ne prendre en compte que le mot de passe invalide afin d'éviter un trop grand nombre d'échec.
        try {
            $user = $userProvider->loadUserByUsername($credentials['email']);
        } catch (\Exception $e) {
            // on enregistre les echecs de tentatives de connexions
            $newLoginFailedAttempt = new LoginFailedAttempt($credentials['ipAddress'], $credentials['email']);
            $this->entityManager->persist($newLoginFailedAttempt);
            $this->entityManager->flush();
            throw new CustomUserMessageAuthenticationException('Identifiants incorrects.');
        }

        /*$user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
        if (!$user) {
            // on enregistre les echecs de tentatives de connexions
            $newLoginFailedAttempt = new LoginFailedAttempt($credentials['ipAddress'], $credentials['email']);
            $this->entityManager->persist($newLoginFailedAttempt);
            $this->entityManager->flush();
            throw new CustomUserMessageAuthenticationException('Identifiants incorrects.');
        }*/

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {   
        $password = $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
        if (!$password) {
            // on enregistre les echecs de tentatives de connexions
            $newLoginFailedAttempt = new LoginFailedAttempt($credentials['ipAddress'], $credentials['email']);
            $this->entityManager->persist($newLoginFailedAttempt);
            $this->entityManager->flush();
            throw new CustomUserMessageAuthenticationException('Identifiants incorrects.');
        }
        return $password;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {   
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {   
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        
        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse($this->urlGenerator->generate('admin_user_index'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('admin_account_login');
    }
}
