<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\DtoResponse;
use App\Service\ContenidoService;
use App\Entity\Contenido;
use App\Model\EstadoCompra;
use App\Model\PagoProovedor;


#[Route('/contenidos')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ContenidosController extends AbstractController
{

    private ContenidoService $contentServ;

    public function __construct(ContenidoService $contentServ)
    {
        $this->contentServ = $contentServ;
    } 

     #[Route('/save', name: 'contenido_save', methods: ['POST'])]
    public function saveTarifaController(Request $request): JsonResponse
    {
        $dto = new DtoResponse();
        $data = json_decode($request->getContent(), true);

        $content = new Contenido();

        $fechaPublicacion = null;
        if (!empty($data['fechaPublicacion'])) {
            try {
                $fechaPublicacion = new \DateTime($data['fechaPublicacion']);
            } catch (\Exception $e) {
                return $this->json([
                    'success' => 'ERROR',
                    'message' => "Fecha de publicacion no válida"
                ], 400);
            }
        }

        $fechaPago = null;
        if (!empty($data['fechaPublicacion'])) {
            try {
                $fechaPago = new \DateTime($data['fechaPago']);
            } catch (\Exception $e) {
                return $this->json([
                    'success' => 'ERROR',
                    'message' => "Fecha de pago no válida"
                ], 400);
            }
        }

        try {
            $estadoCompra = EstadoCompra::from($data['estado_compra']);
            $pagoProveedor = PagoProovedor::from($data['pago_proveedor']);
        }catch (\ValueError $e) {
            return $this->json([
                'success' => 'ERROR',
                'message' => 'Valor de estado_compra o pago_proveedor inválido'
            ], 400);
        }

        $content->setTitulo($data['titulo'])
                ->setDescription($data['descripcion'])
                ->setCuerpo($data['cuerpo'])
                ->setTipoContenido($data['tipo_contenido'])
                ->setNumEnlaces($data['num_enlaces'])
                ->setLongitudPalabras($data['longitud_palabras'])
                ->setCategoriaPublicar($data['categoria_publicar'])
                ->setEstadoCompra($estadoCompra)
                ->setUrlPublicacion($data['url_publicacion'])
                ->setMoneda($data['moneda'])
                ->setFechaPublicacion($fechaPublicacion)
                ->setPagoProveedor($pagoProveedor);

        $dto = $this->contentServ->saveContenido($content, $data['proveedor_id'], $data['medio_id']);

    
        if ($dto->getSuccess() == "ERROR") {
            return $this->json($dto->toArray(), 400);
        }

        return $this->json($dto->toArray(), 201);
        
    }

    #[Route('/get/proveedores/{idProveedor}', name: 'contenido_get_proovedores', methods: ['GET'])]
    public function getContenidoByProovedoresController(int $idProveedor): JsonResponse
    {
        $dto = new DtoResponse();
     
        $dto = $this->contentServ->getContenidoByProovedor($idProveedor);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get/medios/dominio', name: 'contenido_get_medios', methods: ['GET'])]
    public function getContenidoByDominioMediosController(Request $request): JsonResponse
    {
        $dto = new DtoResponse();
        $dominio = $request->query->get('dominio');

        $dto = $this->contentServ->getInformeByDominio($dominio);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get/estado_compra', name: 'contenido_get_estado_compra', methods: ['GET'])]
    public function getContenidoByEstadoCompraController(Request $request): JsonResponse
    {
        $dto = new DtoResponse();
        $estado_compra = strtolower(trim($request->query->get('estado_compra', '')));
        
        $dto = $this->contentServ->getContenidoByEstadoCompra($estado_compra);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/get/estado_pago', name: 'contenido_get_estado_pago', methods: ['GET'])]
    public function getContenidoByEstadoPagoController(Request $request): JsonResponse
    {
        $dto = new DtoResponse();
        $estado_pago= strtolower(trim($request->query->get('estado_pago', '')));
        
        $dto = $this->contentServ->getContenidoByEstadoPago($estado_pago);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/update/estado_pago/contenido_id/{idContenido}', name: 'contenido_update_estado_pago', methods: ['PUT'])]
    public function updateContenidoEstadoPagoontroller(Request $request, int $idContenido): JsonResponse
    {
        $dto = new DtoResponse();
        $data = json_decode($request->getContent(), true);

         try {
            $estado_pago = PagoProovedor::from(strtolower(trim($data['estado_pago'])));
            $dto = $this->contentServ->updateEstadoPago($idContenido, $estado_pago);
        }catch (\ValueError $e) {
            return $this->json([
                'success' => 'ERROR',
                'message' => 'Valor de estado_pago inválido'
            ], 400);
        }

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    #[Route('/delete/{idContenido}', name: 'contenido_delete', methods: ['DELETE'])]
    public function deleteContenidoByIdController(int $idContenido): JsonResponse
    {
        $dto = new DtoResponse();
        $dto = $this->contentServ->deleteById($idContenido);

        if($dto->getSuccess() == "ERROR"){
            return $this->json($dto->toArray(), 404);
        }
            return $this->json($dto->toArray(), 200);
    }

    
}
