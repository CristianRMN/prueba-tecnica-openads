<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Model\DtoResponse;

class UserService
{
    private UserRepository $userRepo;
    private UserPasswordHasherInterface $userPasswordHash;

    public function __construct(UserRepository $userRepo, UserPasswordHasherInterface $userPasswordHash)
    {
        $this->userRepo = $userRepo;
        $this->userPasswordHash = $userPasswordHash;
    }


    public function verifyPassword(User $user, string $password) : bool
    {
        return $this->userPasswordHash->isPasswordValid($user, $password);
    }

    public function getUserByEmailAndPassword(string $email, string $password) : bool
    {
        $user = $this->userRepo->findOneBy(['email' => $email]);
        if(!$user || !$this->verifyPassword($user, $password)){
            return false;
        }

        return true;   
    }


}



