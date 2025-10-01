<?php

namespace App\Service;
use App\Entity\Proovedor;
use App\Entity\Medio;
use App\Entity\Tarifa;
use App\Entity\Contenido;
use App\Repository\ProovedorRepository;
use App\Repository\MedioRepository;
use App\Repository\TarifaRepository;
use App\Repository\ContenidoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Model\DtoResponse;
use App\Model\PagoProovedor;

class ContenidoService
{

    private EntityManagerInterface $em;
    private MedioRepository $medioRep;
    private TarifaRepository $tarifaRep;
    private ProovedorRepository $proovRep;
    private ContenidoRepository $conteRep;

    public function __construct(EntityManagerInterface $em, MedioRepository $medioRep, 
                                ProovedorRepository $proovRep, TarifaRepository $tarifaRep, 
                                ContenidoRepository $conteRep)
    {
        $this->em = $em;
        $this->medioRep = $medioRep;
        $this->proovRep = $proovRep;
        $this->tarifaRep = $tarifaRep;
        $this->conteRep = $conteRep;
    }

    public function saveContenido(Contenido $content, int $idProovedor, int $idMedio)
    {
        if((!is_numeric($idMedio) || $idMedio <= 0) || (!is_numeric($idProovedor) || $idProovedor <= 0)){
            return new DtoResponse("ERROR", "Campos ids inválidos", null);
        }

        if(empty(trim($content->getTitulo())) || empty(trim($content->getDescription())) || empty(trim($content->getCuerpo())) || 
         empty(trim($content->getTipoContenido()))  || $content->getNumEnlaces() === null || $content->getLongitudPalabras() === null || 
         empty(trim($content->getCategoriaPublicar())) || empty(trim($content->getMoneda())) || $content->getCreatedAt() === null
         || $content->getUpdatedAt() === null){
            return new DtoResponse("ERROR", "Rellene los campos faltantes", null);
        }

        $proovedor = $this->proovRep->findOneBy(['id' => $idProovedor]);
        if(!$proovedor || $proovedor->getId() === null){
            return new DtoResponse("ERROR", "Este proovedor no existe", null);
        }

        $medio = $this->medioRep->findOneBy(['id' => $idMedio]);
        if(!$medio || $medio->getId() === null){
            return new DtoResponse("ERROR", "Este medio no existe", null);
        }

        $fechaReferencia = $content->getFechaPublicacion() ?? $content->getCreatedAt();

        $tarifa = $this->tarifaRep->createQueryBuilder('t')
        ->andWhere('t.proovedor = :proovedor')
        ->andWhere('t.medio = :medio')
        ->andWhere('t.vigente_desde <= :fecha')
        ->andWhere('t.vigente_hasta IS NULL OR t.vigente_hasta >= :fecha')
        ->setParameter('proovedor', $proovedor)
        ->setParameter('medio', $medio)
        ->setParameter('fecha', $fechaReferencia->format('Y-m-d'))
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        if(!$tarifa || $tarifa->getPrecio() === null){
            return new DtoResponse("ERROR", "Contenido rechazado por no encontrar precio aplicado en tarifas", null);
        }

        $precioAplicado = $tarifa->getPrecio();


        $content->setPrecioAplicado($precioAplicado)
                ->setMedio($medio)
                ->setProovedor($proovedor);
        

        try{
            $this->em->persist($content);
            $this->em->flush();
        }catch(UniqueConstraintViolationException  $ex){
            return new DtoResponse("ERROR", "Este contenido ya está registrado", null);
        }

        return new DtoResponse("SUCCESS", "Contenido registrado correctamente", $content->toArray());
  
    }

    public function getContenidoByProovedor(int $idProovedor)
    {
         if(!is_numeric($idProovedor) || $idProovedor <= 0){
            return new DtoResponse("ERROR", "Rellena los campos faltantes", null);
        }
        
        $contenidos = $this->conteRep->findBy(['proovedor' => $idProovedor]);

        if(empty($contenidos)){
            return new DtoResponse("ERROR", "No hay Contenidos con este proovedor", null);
        }

        $arrayContenidos = array_map(function($contenido){
            return[
            'id' => $contenido->getId(),
            'titulo' => $contenido->getTitulo(),
            'descripcion' => $contenido->getDescription(),
            'tipo_contenido' => $contenido->getTipoContenido(),
            'estado_compra' => $contenido->getEstadoCompra()->value,
            'precio_aplicado' => $contenido->getPrecioAplicado(),
            'moneda' => $contenido->getMoneda(),
            'pago_proveedor' => $contenido->getPagoProveedor()->value,
            'medio' => [
                'id' => $contenido->getMedio()->getId(),
                'nombre' => $contenido->getMedio()->getNombre(),
                'dominio' => $contenido->getMedio()->getDominio(),
            ],
            'proveedor' => [
                'id' => $contenido->getProovedor()->getId(),
                'empresa' => $contenido->getProovedor()->getEmpresa(),
            ],
            'enlaces' => array_map(function($enlace) {
                return [
                    'url_destino' => $enlace->getUrlDestino(),
                    'anchor_text' => $enlace->getAnchorText(),
                    'atributo' => $enlace->getAtributo()->value
                ];
            }, $contenido->getEnlaces()->toArray()),
            'created_at' => $contenido->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $contenido->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $contenidos);

        return new DtoResponse("SUCCESS", "Contenidos disponibles", $arrayContenidos);   
    }


    public function getInformeByDominio(string $dominio)
    {
        if (empty(trim($dominio))) {
            return new DtoResponse("ERROR", "Rellena los campos faltantes", null);
        }

        $medio = $this->medioRep->findOneBy(['dominio' => $dominio]);

        if (!$medio || $medio->getId() === null) {
            return new DtoResponse("ERROR", "No hay Medios con este dominio", null);
        }

        $contenidos = $this->conteRep->findBy(['medio' => $medio->getId()]);

        if (empty($contenidos)) {
            return new DtoResponse("ERROR", "No hay contenidos con este dominio de un medio", null);
        }

        $totalContenidos = count($contenidos);
        $importeTotal = 0;
        $repartoEstadoCompra = [];
        $repartoPagoProveedor = [];

        foreach ($contenidos as $contenido) {
            $importeTotal += $contenido->getPrecioAplicado();

            $estadoCompra = $contenido->getEstadoCompra()->value;
            if (!isset($repartoEstadoCompra[$estadoCompra])) {
                $repartoEstadoCompra[$estadoCompra] = 0;
            }
            $repartoEstadoCompra[$estadoCompra]++;

            $pago = $contenido->getPagoProveedor()->value;
            if (!isset($repartoPagoProveedor[$pago])) {
                $repartoPagoProveedor[$pago] = 0;
            }
            $repartoPagoProveedor[$pago]++;
        }

        $informe = [
            'medio' => [
                'id' => $medio->getId(),
                'nombre' => $medio->getNombre(),
                'dominio' => $medio->getDominio(),
            ],
            'total_contenidos' => $totalContenidos,
            'importe_total' => $importeTotal,
            'reparto_estado_compra' => $repartoEstadoCompra,
            'reparto_pago_proveedor' => $repartoPagoProveedor,
        ];

        return new DtoResponse("SUCCESS", "Informe generado correctamente", $informe);
    }


    public function getContenidoByEstadoCompra(string $estado_compra)
    {
        if(empty(trim($estado_compra)) ){
            return new DtoResponse("ERROR", "Rellena los campos faltantes", null);
        }
        
        $contenidos = $this->conteRep->findBy(['estado_compra' => $estado_compra]);

        if(empty($contenidos)){
            return new DtoResponse("ERROR", "No hay Contenidos con este estado de compra", null);
        }

        $arrayContenidos = array_map(function($contenido){
            return[
            'id' => $contenido->getId(),
            'titulo' => $contenido->getTitulo(),
            'descripcion' => $contenido->getDescription(),
            'tipo_contenido' => $contenido->getTipoContenido(),
            'estado_compra' => $contenido->getEstadoCompra()->value,
            'precio_aplicado' => $contenido->getPrecioAplicado(),
            'moneda' => $contenido->getMoneda(),
            'pago_proveedor' => $contenido->getPagoProveedor()->value,
            'medio' => [
                'id' => $contenido->getMedio()->getId(),
                'nombre' => $contenido->getMedio()->getNombre(),
                'dominio' => $contenido->getMedio()->getDominio(),
            ],
            'proveedor' => [
                'id' => $contenido->getProovedor()->getId(),
                'empresa' => $contenido->getProovedor()->getEmpresa(),
            ],
            'enlaces' => array_map(function($enlace) {
                return [
                    'url_destino' => $enlace->getUrlDestino(),
                    'anchor_text' => $enlace->getAnchorText(),
                    'atributo' => $enlace->getAtributo()->value
                ];
            }, $contenido->getEnlaces()->toArray()),
            'created_at' => $contenido->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $contenido->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $contenidos);

        return new DtoResponse("SUCCESS", "Contenidos disponibles", $arrayContenidos);   
    }


    public function getContenidoByEstadoPago(string $estado_pago)
    {
        if(empty(trim($estado_pago)) ){
            return new DtoResponse("ERROR", "Rellena los campos faltantes", null);
        }
        
        $contenidos = $this->conteRep->findBy(['pago_proveedor' => $estado_pago]);

        if(empty($contenidos)){
            return new DtoResponse("ERROR", "No hay Contenidos con este estado de pago", null);
        }

        $arrayContenidos = array_map(function($contenido){
            return[
            'id' => $contenido->getId(),
            'titulo' => $contenido->getTitulo(),
            'descripcion' => $contenido->getDescription(),
            'tipo_contenido' => $contenido->getTipoContenido(),
            'estado_compra' => $contenido->getEstadoCompra()->value,
            'precio_aplicado' => $contenido->getPrecioAplicado(),
            'moneda' => $contenido->getMoneda(),
            'pago_proveedor' => $contenido->getPagoProveedor()->value,
            'medio' => [
                'id' => $contenido->getMedio()->getId(),
                'nombre' => $contenido->getMedio()->getNombre(),
                'dominio' => $contenido->getMedio()->getDominio(),
            ],
            'proveedor' => [
                'id' => $contenido->getProovedor()->getId(),
                'empresa' => $contenido->getProovedor()->getEmpresa(),
            ],
            'enlaces' => array_map(function($enlace) {
                return [
                    'url_destino' => $enlace->getUrlDestino(),
                    'anchor_text' => $enlace->getAnchorText(),
                    'atributo' => $enlace->getAtributo()->value
                ];
            }, $contenido->getEnlaces()->toArray()),
            'created_at' => $contenido->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $contenido->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $contenidos);

        return new DtoResponse("SUCCESS", "Contenidos disponibles", $arrayContenidos);   
    }


    public function updateEstadoPago(int $idContenido, PagoProovedor $estado_pago)
    {
        if(!is_numeric($idContenido) || $idContenido <= 0){
            return new DtoResponse("ERROR", "Campos ids inválidos", null);
        }

        if(empty(trim($estado_pago->value)) ){
            return new DtoResponse("ERROR", "Rellena los campos faltantes", null);
        }
        
        $contenidos = $this->conteRep->findOneBy(['id' => $idContenido]);

        if(empty($contenidos)){
            return new DtoResponse("ERROR", "No hay Contenidos con este id: $idContenido", null);
        }


        if($estado_pago->value == "pagado"){
            $contenidos->setPagoProveedor($estado_pago);
            $contenidos->setFechaPago(new \DateTimeImmutable());
        }
        $contenidos->setPagoProveedor($estado_pago);

        $this->em->flush();

        return new DtoResponse("SUCCESS", "Contenido Actualizado", $contenidos->toArray());   
    }

    public function deleteById(int $idContenido)
    {
        if(!is_numeric($idContenido) || $idContenido <= 0){
            return new DtoResponse("ERROR", "Campos ids inválidos", null);
        }
        
        $contenidos = $this->conteRep->findOneBy(['id' => $idContenido]);

        if(empty($contenidos)){
            return new DtoResponse("ERROR", "No hay Contenidos con este id: $idContenido", null);
        }

        $this->em->remove($contenidos);
        $this->em->flush();

        return new dtoResponse("SUCCESS", "Contenido eliminado con éxito", $contenidos->toArray());
    }


    

}