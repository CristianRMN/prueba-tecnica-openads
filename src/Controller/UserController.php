<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use App\Service\UserService;


#[Route('/auth')]
final class UserController extends AbstractController
{
    
    private UserService $userService;
    private JWTEncoderInterface $jwtEncoder;

    public function __construct(UserService $userService, JWTEncoderInterface $jwtEncoder)
    {
        $this->userService = $userService;
        $this->jwtEncoder = $jwtEncoder;
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function loginUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $success = $this->userService->getUserByEmailAndPassword(
            $data['email'],
            $data['password'],
        ); 

        if(!$success){
            return $this->json([['success' => 'ERROR', 'message' => 'Credenciales incorrectas'], 401]);
        }


        $token = $this->jwtEncoder->encode([
            'email' => $data['email'],
            'exp' => time() + 3600,
        ]);

        return $this->json(['message' => 'Usuario logueado correctamente', 'token' => $token], 201);
    }

    
}
