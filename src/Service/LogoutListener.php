<?php
    namespace App\Service;

    use App\Entity\User;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
    use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class LogoutListener implements LogoutHandlerInterface
{
    private $managerRegistry;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry=$managerRegistry;
    }
   
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        //$this->getToken()->getUserIdentifier();
        $user = $token->getUserIdentifier();  
        $logout=$this->managerRegistry->getRepository(User::class)->findOneBy(['username'=>$user]);
        $logout->setIsconnected(0);
        $this->managerRegistry->getManager()->flush();    
    }
        
}