<?php


namespace App\Service;

use App\Entity\Proovedor;
use App\Entity\Medio;
use App\Entity\Tarifa;
use App\Repository\ProovedorRepository;
use App\Repository\MedioRepository;
use App\Repository\TarifaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Model\DtoResponse;


class TarifaService
{
    private EntityManagerInterface $em;
    private MedioRepository $medioRep;
    private TarifaRepository $tarifaRep;
    private ProovedorRepository $proovRep;

    public function __construct(EntityManagerInterface $em, MedioRepository $medioRep, 
                                ProovedorRepository $proovRep, TarifaRepository $tarifaRep)
    {
        $this->em = $em;
        $this->medioRep = $medioRep;
        $this->proovRep = $proovRep;
        $this->tarifaRep = $tarifaRep;
    }


    public function saveTarifa(Tarifa $tarifa, int $idProov, int $idMedio): DtoResponse
    {

        if($idProov === null || $idMedio === null || $tarifa->getVigenteDesde() === null){
            return new DtoResponse("ERROR", "Rellena los proovedores, medios y fecha vigente", null);
        }

        $precio = (float) $tarifa->getPrecio();

        if (!is_numeric($precio) || $precio < 0) {
            return new DtoResponse("ERROR", "Precio no válido", null);
        }

        $monedasValidas = ['EUR', 'USD', 'GBP'];

        if (!in_array(strtoupper($tarifa->getMoneda()), $monedasValidas)) {
            return new DtoResponse("ERROR", "Moneda no válida", null);
        }

        $desde = $tarifa->getVigenteDesde();
        $hasta = $tarifa->getVigenteHasta();

        if (!$desde instanceof \DateTime) {
            return new DtoResponse("ERROR", "La fecha 'vigente_desde' no es válida", null);
        }

        if ($hasta !== null && !$hasta instanceof \DateTime) {
            return new DtoResponse("ERROR", "La fecha 'vigente_hasta' no es válida", null);
        }

        if ($hasta !== null && $desde > $hasta) {
            return new DtoResponse("ERROR", "'vigente_hasta' debe ser posterior a 'vigente_desde'", null);
        }

        $proovedor = $this->proovRep->findOneBy(['id' => $idProov]);
        if(!$proovedor || $proovedor->getId() === null){
            return new DtoResponse("ERROR", "Este proovedor no existe", null);
        }


        $medio = $this->medioRep->findOneBy(['id' => $idMedio]);
        if(!$medio || $medio->getId() === null){
            return new DtoResponse("ERROR", "Este medio no existe", null);
        }

        $tarifa->setProovedorId($proovedor)
               ->setMedioId($medio);
        
        try{
            $this->em->persist($tarifa);
            $this->em->flush();
        }catch(UniqueConstraintViolationException  $ex){
            return new DtoResponse("ERROR", "Esta tarifa ya está registrada", null);
        }
        return new DtoResponse("SUCCESS", "Tarifa registrada correctamente", $tarifa->toArray());
    }

    public function getTarifaAll(): DtoResponse
    {
     
        $tarifas = $this->tarifaRep->findAll();

        if(empty($tarifas)){
            return new DtoResponse("ERROR", "No hay tarifas con este proovedor", null);
        }

        $arrayTarifas = array_map(function($tarifa){
            return[
                'id' => $tarifa->getId(),
                'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),  
                'medio' => $tarifa->getMedioId()?->getNombre(),           
                'precio' => $tarifa->getPrecio(),
                'moneda' => $tarifa->getMoneda(),
                'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
            ];
        }, $tarifas);

        return new DtoResponse("SUCCESS", "Tarifas disponibles", $arrayTarifas);   

    }

    public function getTarifaByProovedorAndIdTarifa(int $id_tarifa, int $id_proovedor): DtoResponse
    {
        if($id_tarifa === null || $id_proovedor === null){
            return new DtoResponse("ERROR", "Rellena los campos faltantes", null);
        }
        
        $tarifas = $this->tarifaRep->findBy(['id' => $id_tarifa, 'proovedor' => $id_proovedor]);

        if(empty($tarifas)){
            return new DtoResponse("ERROR", "No hay tarifas con este proovedor", null);
        }

        $arrayTarifas = array_map(function($tarifa){
            return[
                'id' => $tarifa->getId(),
                'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),  
                'medio' => $tarifa->getMedioId()?->getNombre(),           
                'precio' => $tarifa->getPrecio(),
                'moneda' => $tarifa->getMoneda(),
                'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
            ];
        }, $tarifas);

        return new DtoResponse("SUCCESS", "Tarifas disponibles", $arrayTarifas);   


    }

    public function getTarifaByProovedor(int $id_proovedor): DtoResponse
    {
        if(!is_numeric($id_proovedor) || $id_proovedor <= 0){
            return new DtoResponse("ERROR", "Rellena los campos faltantes", null);
        }
        
        $tarifas = $this->tarifaRep->findBy(['proovedor' => $id_proovedor]);

        if(empty($tarifas)){
            return new DtoResponse("ERROR", "No hay tarifas con este proovedor", null);
        }

        $arrayTarifas = array_map(function($tarifa){
            return[
                'id' => $tarifa->getId(),
                'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),  
                'medio' => $tarifa->getMedioId()?->getNombre(),           
                'precio' => $tarifa->getPrecio(),
                'moneda' => $tarifa->getMoneda(),
                'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
            ];
        }, $tarifas);

        return new DtoResponse("SUCCESS", "Tarifas disponibles", $arrayTarifas);   


    }


    public function getTarifaByMedioAndIdTarifa(int $id_tarifa, int $id_medio): DtoResponse
    {
         if((!is_numeric($id_tarifa) || $id_tarifa <= 0) || (!is_numeric($id_medio) || $id_medio <= 0) ){
            return new DtoResponse("ERROR", "Campos inválidos", null);
        }
        
        $tarifas = $this->tarifaRep->findBy(['id' => $id_tarifa, 'medio' => $id_medio]);

        if(empty($tarifas)){
            return new DtoResponse("ERROR", "No hay tarifas con este medio", null);
        }

        $arrayTarifas = array_map(function($tarifa){
            return[
                'id' => $tarifa->getId(),
                'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),  
                'medio' => $tarifa->getMedioId()?->getNombre(),           
                'precio' => $tarifa->getPrecio(),
                'moneda' => $tarifa->getMoneda(),
                'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
            ];
        }, $tarifas);

        return new DtoResponse("SUCCESS", "Tarifas disponibles", $arrayTarifas);   


    }

    public function getTarifaByMedio(int $id_medio): DtoResponse
    {
        if(!is_numeric($id_medio) || $id_medio <= 0){
            return new DtoResponse("ERROR", "Campos inválidos", null);
        }
        
        $tarifas = $this->tarifaRep->findBy(['medio' => $id_medio]);

        if(empty($tarifas)){
            return new DtoResponse("ERROR", "No hay tarifas con este medio", null);
        }

        $arrayTarifas = array_map(function($tarifa){
            return[
                'id' => $tarifa->getId(),
                'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),  
                'medio' => $tarifa->getMedioId()?->getNombre(),           
                'precio' => $tarifa->getPrecio(),
                'moneda' => $tarifa->getMoneda(),
                'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
            ];
        }, $tarifas);

        return new DtoResponse("SUCCESS", "Tarifas disponibles", $arrayTarifas);   


    }


    public function getTarifaBetweenFechas(\DateTime $fechaInicio, \DateTime $fechaFin): DtoResponse
    {
        if($fechaInicio === null || $fechaFin === null){
            return new DtoResponse("ERROR", "Campos inválidos", null);
        }
                
        $qb = $this->tarifaRep->createQueryBuilder('t')
            ->where('t.vigente_desde <= :fechaFin')
            ->andWhere('(t.vigente_hasta IS NULL OR t.vigente_hasta >= :fechaInicio)')
            ->setParameter('fechaInicio', $fechaInicio->format('Y-m-d'))
            ->setParameter('fechaFin', $fechaFin->format('Y-m-d'));


        $tarifas = $qb->getQuery()->getResult();


       if(empty($tarifas)){
            return new DtoResponse("ERROR", "No hay tarigas para el rango de fechas", null);
        } 

        $arrayTarifas = array_map(function($tarifa){
            return[
                'id' => $tarifa->getId(),
                'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),  
                'medio' => $tarifa->getMedioId()?->getNombre(),           
                'precio' => $tarifa->getPrecio(),
                'moneda' => $tarifa->getMoneda(),
                'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
            ];
        }, $tarifas);

        return new DtoResponse("SUCCESS", "Tarifas disponibles", $arrayTarifas);   

    }


    public function updatePrecioTarifas(int $idTarifa, float $newPrecio): DtoResponse
    {
      if((!is_numeric($idTarifa) || $idTarifa <= 0) || (!is_numeric($newPrecio) || $newPrecio <= 0)){
            return new DtoResponse("ERROR", "Campos inválidos", null);
        }
        
        $tarifa = $this->tarifaRep->findOneBy(['id' => $idTarifa]);

        if(!$tarifa || $tarifa->getId() === null){
            return new DtoResponse("ERROR", "No hay tarifa para el id: $idTarifa", null);
        }

        $tarifa->setPrecio($newPrecio);
        $this->em->flush();

        return new DtoResponse("SUCCESS", "Tarifas actualizada", $tarifa->toArray());   

    }


    public function deleteTarifas(int $idTarifa): DtoResponse
    {
      if(!is_numeric($idTarifa) || $idTarifa <= 0){
            return new DtoResponse("ERROR", "Campos inválidos", null);
        }
        
        $tarifa = $this->tarifaRep->findOneBy(['id' => $idTarifa]);

        if(!$tarifa || $tarifa->getId() === null){
            return new DtoResponse("ERROR", "No hay tarifa para el id: $idTarifa", null);
        }

        $this->em->remove($tarifa);
        $this->em->flush();

        return new dtoResponse("SUCCESS", "Tarifa eliminada con éxito", $tarifa->toArray());
    }

    
}