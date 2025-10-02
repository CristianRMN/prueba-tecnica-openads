<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\DtoResponse;
use App\Service\MedioService;
use App\Entity\Medio;


#[Route('/medio')]
final class MedioController extends AbstractController
{

    private MedioService $medioServ;

    public function __construct(MedioService $medioServ)
    {
        $this->medioServ = $medioServ;
    } 

    #[Route('/save', name: 'medio_save', methods: ['POST'])]
    public function saveMedioController(Request $request): JsonResponse
    {
    
        $dto = new DtoResponse();
        $data = json_decode($request->getContent(), true);


        $medio = new Medio();

        $medio->setNombre($data['nombre'])
                  ->setDominio($data['dominio'])
                  ->setCategoria($data['categoria'])
                  ->setTematicasDelicadas($data['tematicas_delicadas'])
                  ->setNumEnlacesPermitidos($data['num_enlaces_permitidos'])
                  ->setTiposEnlacePermitidos($data['tiposEnlacePermitidos'])
                  ->setPublicaPortada($data['publica_portada'])
                  ->setPublicaCategorias($data['publica_categorias'])
                  ->setTematicasNoAceptadas($data['tematicas_no_aceptadas'])
                  ->setIndicaPatrocinado($data['indica_patrocinado'])
                  ->setTraficoMes($data['trafico_mes'])
                  ->setDa($data['da'])
                  ->setDr($data['dr']);

        
        $dto = $this->medioServ->saveMedio($medio);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 400);
        }
        return $this->json($dto->toArray(), 201);

    }

    #[Route('/getAll', name: 'medios_get_all', methods: ['GET'])]
    public function getAllMediosController(): JsonResponse
    {
        $dto = new DtoResponse();
     
        $dto = $this->medioServ->getAllMedios();

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get_categorias', name: 'medios_get_categorias', methods: ['GET'])]
    public function getMediosByCategoriasController(Request $request): JsonResponse
    {
        $categoria = $request->query->get('categoria');

        $dto = new DtoResponse();
     
        $dto = $this->medioServ->getMediosByCategorias($categoria);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get_publica_portada', name: 'medios_get_publica_portada', methods: ['GET'])]
    public function getMediosByPublicaPortadaController(Request $request): JsonResponse
    {
        $publica_portada = $request->query->get('publica_portada');

        $publica_portada = filter_var($request->query->get('publica_portada'), FILTER_VALIDATE_BOOLEAN);

        $dto = new DtoResponse();
     
        $dto = $this->medioServ->getMediosIsPublicaPortada($publica_portada);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get_trafico_mes', name: 'medios_get_trafico_mes', methods: ['GET'])]
    public function getMediosByTraficoMesController(Request $request): JsonResponse
    {
        $trafico_mes = (int) $request->query->get('trafico_mes');

        $dto = new DtoResponse();
     
        $dto = $this->medioServ->getTraficoMes($trafico_mes);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);

    }

    #[Route('/update/{id}', name: 'medios_update', methods: ['PUT'])]
    public function updateMediosController(int $id, Request $request): JsonResponse
    {
        $dto = new DtoResponse();
        $data = json_decode($request->getContent(), true);

        $medio = new Medio();

        $medio->setNombre($data['nombre'])
                  ->setDominio($data['dominio'])
                  ->setCategoria($data['categoria'])
                  ->setTematicasDelicadas($data['tematicas_delicadas'])
                  ->setNumEnlacesPermitidos($data['num_enlaces_permitidos'])
                  ->setTiposEnlacePermitidos($data['tiposEnlacePermitidos'])
                  ->setPublicaPortada($data['publica_portada'])
                  ->setPublicaCategorias($data['publica_categorias'])
                  ->setTematicasNoAceptadas($data['tematicas_no_aceptadas'])
                  ->setIndicaPatrocinado($data['indica_patrocinado'])
                  ->setTraficoMes($data['trafico_mes'])
                  ->setDa($data['da'])
                  ->setDr($data['dr']);
        
        $dto = $this->medioServ->updateMedioById($id, $medio);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }


    
    #[Route('/delete/{id}', name: 'medios_delete', methods: ['DELETE'])]
    public function deleteMedioController(int $id): JsonResponse
    {
        $dto = new DtoResponse();
        
        $dto = $this->medioServ->deleteMedio($id);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }
        
}
