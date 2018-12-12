<?php

namespace App\EventSubscriber;

use App\Event\CreateUserEvent;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        FileUploader $fileUploader,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        SessionInterface $session
        )
    {

        $this->fileUploader = $fileUploader;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function onUserRegister(CreateUserEvent $event)
    {
        $user = $event->getUser();
        $file = $user->getAvatar();
        $fileName = $this->fileUploader->upload($file);
        $user->setAvatar($fileName);
        $user->setRoles(array('ROLE_USER'));

        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'Vous vous êtes bien enregistré.e');
    }

    public static function getSubscribedEvents()
    {
        return [
           CreateUserEvent::NAME => [
               ['onUserRegister', 0]
           ]
        ];
    }
}
