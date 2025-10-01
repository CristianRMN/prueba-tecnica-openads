<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\DtoResponse;
use App\Service\TarifaService;
use App\Entity\Tarifa;


#[Route('/tarifa')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]

final class TarifaController extends AbstractController
{

    private TarifaService $tarifaServ;

    public function __construct(TarifaService $tarifaServ)
    {
        $this->tarifaServ = $tarifaServ;
    } 

    #[Route('/save', name: 'tarifa_save', methods: ['POST'])]
    public function saveTarifaController(Request $request): JsonResponse
    {
        $dto = new DtoResponse();
        $data = json_decode($request->getContent(), true);

        $tarifa = new Tarifa();

        try {
            $vigenteDesde = new \DateTime($data['vigente_desde']);
        } catch (\Exception $e) {
            return $this->json([
                'success' => 'ERROR',
                'message' => "Fecha 'vigente_desde' no válida"
            ], 400);
        }

        $vigenteHasta = null;
        if (!empty($data['vigente_hasta'])) {
            try {
                $vigenteHasta = new \DateTime($data['vigente_hasta']);
            } catch (\Exception $e) {
                return $this->json([
                    'success' => 'ERROR',
                    'message' => "Fecha 'vigente_hasta' no válida"
                ], 400);
            }
        }

        $tarifa->setPrecio($data['precio'])
            ->setMoneda($data['moneda'])
            ->setVigenteDesde($vigenteDesde)
            ->setVigenteHasta($vigenteHasta);

        $dto = $this->tarifaServ->saveTarifa($tarifa, $data['proveedor_id'], $data['medio_id']);

        if ($dto->getSuccess() == "ERROR") {
            return $this->json($dto->toArray(), 400);
        }

        return $this->json($dto->toArray(), 201);
    }

    
    #[Route('/getAll', name: 'tarifa_get_All', methods: ['GET'])]
    public function getTarifaAllController(): JsonResponse
    {
        $dto = new DtoResponse();
    
        $dto = $this->tarifaServ->getTarifaAll();

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get/proveedores/{idProveedor}/tarifas/{idTarifa}', name: 'tarifa_get_proovedores', methods: ['GET'])]
    public function getTarifaByProovedoresAndIdTarifasController(int $idProveedor, int $idTarifa): JsonResponse
    {
        $dto = new DtoResponse();
     
        $dto = $this->tarifaServ->getTarifaByProovedorAndIdTarifa($idTarifa, $idProveedor);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get/proveedores/{idProveedor}', name: 'tarifa_get_proovedores', methods: ['GET'])]
    public function getTarifaByProovedoresController(int $idProveedor): JsonResponse
    {
        $dto = new DtoResponse();
     
        $dto = $this->tarifaServ->getTarifaByProovedor($idProveedor);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }


    #[Route('/get/medios/{idMedio}/tarifas/{idTarifa}', name: 'tarifa_get_medios_and_tarifa', methods: ['GET'])]
    public function getTarifaByMediosAndIdTarifasController(int $idMedio, int $idTarifa): JsonResponse
    {
        $dto = new DtoResponse();
     
        $dto = $this->tarifaServ->getTarifaByMedioAndIdTarifa($idTarifa, $idMedio);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get/medios/{idMedios}', name: 'tarifa_get_medios', methods: ['GET'])]
    public function getTarifaByMediosController(int $idMedios): JsonResponse
    {
        $dto = new DtoResponse();
     
        $dto = $this->tarifaServ->getTarifaByMedio($idMedios);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get/fecha_vigente', name: 'tarifa_get_fecha_vigente', methods: ['GET'])]
    public function getTarifaByFechasVigentesController(Request $request): JsonResponse
    {
        
        $fecha_inicio = $request->query->get('fecha_inicio');
        $fecha_fin = $request->query->get('fecha_fin');

        try {
            $fecha_inicio = new \DateTime();
        } catch (\Exception $e) {
            return $this->json([
                'success' => 'ERROR',
                'message' => "Fecha inicio no válida"
            ], 400);
        }

        
        try {
            $fecha_fin = new \DateTime();
            } catch (\Exception $e) {
            return $this->json([
                'success' => 'ERROR',
                'message' => "Fecha fin no válida"
            ], 400);
        }
        
        $dto = new DtoResponse();
     
        $dto = $this->tarifaServ->getTarifaBetweenFechas($fecha_inicio, $fecha_fin);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
            
    }

    #[Route('/update/precio/id/{idTarifa}', name: 'tarifa_update_precio', methods: ['PUT'])]
    public function updatePrecioTarifaController(int $idTarifa, Request $request): JsonResponse
    {
        $dto = new DtoResponse();
        $data = json_decode($request->getContent(), true);

        $dto = $this->tarifaServ->updatePrecioTarifas($idTarifa, $data['precio']);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
            
        
    }

    #[Route('/delete/id/{idTarifa}', name: 'tarifa_delete', methods: ['DELETE'])]
    public function deletePrecioTarifaController(int $idTarifa): JsonResponse
    {
        $dto = new DtoResponse();

        $dto = $this->tarifaServ->deleteTarifas($idTarifa);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
            
    }

    


}
