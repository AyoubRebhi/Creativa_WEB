<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AuthAuthenticator extends AbstractLoginFormAuthenticator
{    private AuthorizationCheckerInterface $authorizationChecker;

    private SessionInterface $session;

    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator,SessionInterface $session,AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->session = $session;
        $this->authorizationChecker = $authorizationChecker;


    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        $user = $token->getUser();

        // Récupérer l'ID de l'utilisateur
        $userId = $user->getIdUser();

        // Stocker l'ID de l'utilisateur dans la session
        $this->session->set('user_id', $userId);
// Vérifiez le rôle de l'utilisateur
if ( $user->getRole()=='ARTIST') {
    // Redirection pour l'utilisateur ayant le rôle admin
    return new RedirectResponse('/Artistpage');
}elseif ($user->getRole()=='CLIENT') {
    return new RedirectResponse('/userpage');
} elseif ($user->getRole()=='ADMIN') {
    return new RedirectResponse('/Adminpage');
} else {
    // Gérer d'autres rôles ou cas par défaut
}

// } else if ($this->authorizationChecker->isGranted('ARTIST')) {
//     return new RedirectResponse('/Artistpage"');

//     // Redirection pour l'utilisateur ayant le rôle user
// } else {    if ($this->authorizationChecker->isGranted('ClIENT'))         
//     {return new RedirectResponse('/userpage');}
    

//     // Redirection par défaut pour les autres utilisateurs
// }
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
