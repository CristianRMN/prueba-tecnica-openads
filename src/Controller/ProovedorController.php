<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\DtoResponse;
use App\Service\ProovedorService;
use App\Entity\Proovedor;


#[Route('/proovedor')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]

final class ProovedorController extends AbstractController
{
    
    private ProovedorService $proovSer;

    public function __construct(ProovedorService $proovSer)
    {
        $this->proovSer = $proovSer;
    } 

    #[Route('/save', name: 'proovedor_save', methods: ['POST'])]
    public function saveProovedorController(Request $request): JsonResponse
    {

        $dto = new DtoResponse();
        $data = json_decode($request->getContent(), true);


        $proovedor = new Proovedor();

        $proovedor->setContacto($data['contacto'])
                  ->setEmpresa($data['empresa'])
                  ->setEmail($data['email'])
                  ->setTelefono($data['telefono'])
                  ->setDireccion($data['direccion'])
                  ->setCp($data['cp'])
                  ->setCiudad($data['ciudad'])
                  ->setProvincia($data['provincia'])
                  ->setPais($data['pais']);
        
        $dto = $this->proovSer->saveProovedor($proovedor);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 400);
        }
        return $this->json($dto->toArray(), 201);

    }

    #[Route('/getAll', name: 'proovedores_get_all', methods: ['GET'])]
    public function getAllProovedoresController(): JsonResponse
    {
        $dto = new DtoResponse();
     
        $dto = $this->proovSer->getAllProovedores();

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get_empresa', name: 'proovedores_get_empresa', methods: ['GET'])]
    public function getProovedoresByEmpresaController(Request $request): JsonResponse
    {
        $empresa = $request->query->get('empresa');
        $dto = new DtoResponse();
     
        $dto = $this->proovSer->getProovedoresbyEmpresa($empresa);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get_ciudad', name: 'proovedores_get_ciudad', methods: ['GET'])]
    public function getProovedoresByCiudadController(Request $request): JsonResponse
    {
        $ciudad = $request->query->get('ciudad');
        $dto = new DtoResponse();
     
        $dto = $this->proovSer->getProovedoresbyCiudad($ciudad);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get_email', name: 'proovedores_get_email', methods: ['GET'])]
    public function getProovedoresByEmailController(Request $request): JsonResponse
    {
        $email = $request->query->get('email');
        $dto = new DtoResponse();
     
        $dto = $this->proovSer->getProovedoresbyEmail($email);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/update/{id}', name: 'proovedores_update', methods: ['PUT'])]
    public function updateProovedoresController(int $id, Request $request): JsonResponse
    {
        $dto = new DtoResponse();
        $data = json_decode($request->getContent(), true);

        $proovedor = new Proovedor();

        $proovedor->setContacto($data['contacto'])
                  ->setEmpresa($data['empresa'])
                  ->setEmail($data['email'])
                  ->setTelefono($data['telefono'])
                  ->setDireccion($data['direccion'])
                  ->setCp($data['cp'])
                  ->setCiudad($data['ciudad'])
                  ->setProvincia($data['provincia'])
                  ->setPais($data['pais']);
        
        $dto = $this->proovSer->updateProovedorById($id, $proovedor);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/delete/{id}', name: 'proovedores_delete', methods: ['DELETE'])]
    public function deleteProovedoresController(int $id): JsonResponse
    {
        $dto = new DtoResponse();
        
        $dto = $this->proovSer->deleteProovedor($id);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }


}
