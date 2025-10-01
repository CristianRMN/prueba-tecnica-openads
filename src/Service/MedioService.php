<?php

namespace App\Service;

use App\Entity\Medio;
use App\Repository\MedioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Model\DtoResponse;

class MedioService
{

    private EntityManagerInterface $em;
    private MedioRepository $medioRep;

    public function __construct(EntityManagerInterface $em, MedioRepository $medioRep)
    {
        $this->em = $em;
        $this->medioRep = $medioRep;
    }

    public function saveMedio(Medio $medio): DtoResponse
    {
        if(empty(trim($medio->getNombre())) || empty(trim($medio->getDominio())) || empty(trim($medio->getCategoria())) || 
         $medio->getNumEnlacesPermitidos() === null || $medio->isPublicaPortada() === null || $medio->isPublicaCategorias() === null || 
         $medio->isIndicaPatrocinado() === null || $medio->getTraficoMes() === null || $medio->getCreatedAt() === null){
            return new DtoResponse("ERROR", "Rellene los campos faltantes", null);
        }

        $existeDominio = $this->medioRep->findOneBy(['dominio' => $medio->getDominio()]);
        if ($existeDominio) {
            return new DtoResponse("ERROR", "Ese dominio ya está registrado", null);
        }

        $dominioNormalizado = $this->normalizarDominio($medio->getDominio());

        if($medio->getNumEnlacesPermitidos() <= 0 || $medio->getTraficoMes() <= 0){
            return new DtoResponse("ERROR", "El numero de enlaces es incorrecto", null);   
        }

        if ($medio->getDa() !== null && ($medio->getDa() < 0 || $medio->getDa() > 100)) {
            return new DtoResponse("ERROR", "El DA debe estar entre 0 y 100", null);
        }

        if ($medio->getDr() !== null && ($medio->getDr() < 0 || $medio->getDr() > 100)) {
            return new DtoResponse("ERROR", "El DR debe estar entre 0 y 100", null);
        }

        $enlacesPermitidos = ['dofollow', 'nofollow', 'sponsored'];

        $tipos = $medio->getTiposEnlacePermitidos();

        if (empty($tipos)) {
            return new DtoResponse("ERROR", "Debe indicar al menos un tipo de enlace permitido", null);
        }

        foreach ($tipos as $tipo) {
            if (!in_array($tipo, $enlacesPermitidos)) {
                return new DtoResponse("ERROR", "Tipo de enlace no válido: $tipo", null);
            }
        }

        try{
            $this->em->persist($medio);
            $this->em->flush();
        }catch(UniqueConstraintViolationException  $ex){
            return new DtoResponse("ERROR", "Este medio ya está registrado", null);
        }

        return new DtoResponse("SUCCESS", "Medio registrado correctamente", $medio->toArray());

    }

    public function getAllMedios(): DtoResponse
    {
        $medios = $this->medioRep->findAll();

        if(!is_array($medios)){
            return new DtoResponse("ERROR", "Error al recibir los medios", null);
        } 

        $arrayMedios = array_map(function($medio){
            return[
                'id' => $medio->getId(),
                'nombre' => $medio->getNombre(),
                'dominio' => $medio->getDominio(),
                'categoria' => $medio->getCategoria(),
                'tematicas_delicadas' => $medio->getTematicasDelicadas(),
                'num_enlaces_permitidos' => $medio->getNumEnlacesPermitidos(),
                'tipos_enlace_permitidos' => $medio->getTiposEnlacePermitidos(),
                'publica_portada' => $medio->isPublicaPortada(),
                'publica_categorias' => $medio->isPublicaCategorias(),
                'tematicas_no_aceptadas' => $medio->getTematicasNoAceptadas(),
                'indica_patrocinado' => $medio->isIndicaPatrocinado(),
                'trafico_mes' => $medio->getTraficoMes(),
                'da' => $medio->getDa(),
                'dr' => $medio->getDr(),
                'created_at' => $medio->getCreatedAt()?->format('Y-m-d H:i:s'),
                'num_contenidos' => $medio->getContenidos()->count(),
                'tarifas' => array_map(function($tarifa){
                    return [
                        'id' => $tarifa->getId(),
                        'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),
                        'precio' => $tarifa->getPrecio(),
                        'moneda' => $tarifa->getMoneda(),
                        'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                        'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
                    ];
                }, $medio->getTarifas()->toArray()),
            ];
        }, $medios);

        return new DtoResponse("SUCCESS", "Medios disponibles", $arrayMedios);   

    }


    public function getMediosByCategorias(string $categoria): DtoResponse
    {

        if(empty(trim($categoria))){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        $medios = $this->medioRep->findBy(['categoria' => $categoria]);

        if(empty($medios)){
            return new DtoResponse("ERROR", "No existe la categoria $categoria", null);
        } 

        $arrayMedios = array_map(function($medio){
            return[
                'id' => $medio->getId(),
                'nombre' => $medio->getNombre(),
                'dominio' => $medio->getDominio(),
                'categoria' => $medio->getCategoria(),
                'tematicas_delicadas' => $medio->getTematicasDelicadas(),
                'num_enlaces_permitidos' => $medio->getNumEnlacesPermitidos(),
                'tipos_enlace_permitidos' => $medio->getTiposEnlacePermitidos(),
                'publica_portada' => $medio->isPublicaPortada(),
                'publica_categorias' => $medio->isPublicaCategorias(),
                'tematicas_no_aceptadas' => $medio->getTematicasNoAceptadas(),
                'indica_patrocinado' => $medio->isIndicaPatrocinado(),
                'trafico_mes' => $medio->getTraficoMes(),
                'da' => $medio->getDa(),
                'dr' => $medio->getDr(),
                'created_at' => $medio->getCreatedAt()?->format('Y-m-d H:i:s'),
                'num_contenidos' => $medio->getContenidos()->count(),
                'tarifas' => array_map(function($tarifa){
                    return [
                        'id' => $tarifa->getId(),
                        'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),
                        'precio' => $tarifa->getPrecio(),
                        'moneda' => $tarifa->getMoneda(),
                        'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                        'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
                    ];
                }, $medio->getTarifas()->toArray()),
            ];
        }, $medios);

        return new DtoResponse("SUCCESS", "Medios disponibles", $arrayMedios);   

    }


    public function getMediosIsPublicaPortada(bool $publica_portada): DtoResponse
    {

        if($publica_portada === null){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        $medios = $this->medioRep->findBy(['publica_portada' => $publica_portada]);

        if(empty($medios)){
            return new DtoResponse("ERROR", "No existe el valor $publica_portada", null);
        } 

        $arrayMedios = array_map(function($medio){
            return[
                'id' => $medio->getId(),
                'nombre' => $medio->getNombre(),
                'dominio' => $medio->getDominio(),
                'categoria' => $medio->getCategoria(),
                'tematicas_delicadas' => $medio->getTematicasDelicadas(),
                'num_enlaces_permitidos' => $medio->getNumEnlacesPermitidos(),
                'tipos_enlace_permitidos' => $medio->getTiposEnlacePermitidos(),
                'publica_portada' => $medio->isPublicaPortada(),
                'publica_categorias' => $medio->isPublicaCategorias(),
                'tematicas_no_aceptadas' => $medio->getTematicasNoAceptadas(),
                'indica_patrocinado' => $medio->isIndicaPatrocinado(),
                'trafico_mes' => $medio->getTraficoMes(),
                'da' => $medio->getDa(),
                'dr' => $medio->getDr(),
                'created_at' => $medio->getCreatedAt()?->format('Y-m-d H:i:s'),
                'num_contenidos' => $medio->getContenidos()->count(),
                'tarifas' => array_map(function($tarifa){
                    return [
                        'id' => $tarifa->getId(),
                        'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),
                        'precio' => $tarifa->getPrecio(),
                        'moneda' => $tarifa->getMoneda(),
                        'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                        'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
                    ];
                }, $medio->getTarifas()->toArray()),
            ];
        }, $medios);

        return new DtoResponse("SUCCESS", "Medios disponibles", $arrayMedios);   

    }

    public function getTraficoMes(int $trafico_mes): DtoResponse
    {

        if($trafico_mes === null){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        if($trafico_mes <= 0){
            return new DtoResponse("ERROR", "Trafico mes no puede ser negativo o 0 y debe ser numero", null); 
        }

        $medios = $this->medioRep->createQueryBuilder('a')
                 ->where('a.trafico_mes >= :trafico_mes')
                 ->setParameter('trafico_mes', $trafico_mes)
                 ->getQuery()
                 ->getResult();

        if(empty($medios)){
            return new DtoResponse("ERROR", "No hay medios para el rango de $trafico_mes", null);
        } 

        $arrayMedios = array_map(function($medio){
            return[
                'id' => $medio->getId(),
                'nombre' => $medio->getNombre(),
                'dominio' => $medio->getDominio(),
                'categoria' => $medio->getCategoria(),
                'tematicas_delicadas' => $medio->getTematicasDelicadas(),
                'num_enlaces_permitidos' => $medio->getNumEnlacesPermitidos(),
                'tipos_enlace_permitidos' => $medio->getTiposEnlacePermitidos(),
                'publica_portada' => $medio->isPublicaPortada(),
                'publica_categorias' => $medio->isPublicaCategorias(),
                'tematicas_no_aceptadas' => $medio->getTematicasNoAceptadas(),
                'indica_patrocinado' => $medio->isIndicaPatrocinado(),
                'trafico_mes' => $medio->getTraficoMes(),
                'da' => $medio->getDa(),
                'dr' => $medio->getDr(),
                'created_at' => $medio->getCreatedAt()?->format('Y-m-d H:i:s'),
                'num_contenidos' => $medio->getContenidos()->count(),
                'tarifas' => array_map(function($tarifa){
                    return [
                        'id' => $tarifa->getId(),
                        'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),
                        'precio' => $tarifa->getPrecio(),
                        'moneda' => $tarifa->getMoneda(),
                        'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                        'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
                    ];
                }, $medio->getTarifas()->toArray()),
            ];
        }, $medios);

        return new DtoResponse("SUCCESS", "Medios disponibles", $arrayMedios);   

    }


    public function updateMedioById(int $id, Medio $oldMedio)
    {
        if(empty(trim($oldMedio->getNombre())) || empty(trim($oldMedio->getDominio())) || empty(trim($oldMedio->getCategoria())) || 
         $oldMedio->getNumEnlacesPermitidos() === null || $oldMedio->isPublicaPortada() === null || $oldMedio->isPublicaCategorias() === null || 
         $oldMedio->isIndicaPatrocinado() === null || $oldMedio->getTraficoMes() === null || $oldMedio->getCreatedAt() === null){
            return new DtoResponse("ERROR", "Rellene los campos faltantes", null);
        }

        $existeDominio = $this->medioRep->findOneBy(['dominio' => $oldMedio->getDominio()]);
        if ($existeDominio) {
            return new DtoResponse("ERROR", "Ese dominio ya está registrado", null);
        }

        $dominioNormalizado = $this->normalizarDominio($oldMedio->getDominio());

        if($oldMedio->getNumEnlacesPermitidos() <= 0 || $oldMedio->getTraficoMes() <= 0){
            return new DtoResponse("ERROR", "El numero de enlaces es incorrecto", null);   
        }

        if ($oldMedio->getDa() !== null && ($oldMedio->getDa() < 0 || $oldMedio->getDa() > 100)) {
            return new DtoResponse("ERROR", "El DA debe estar entre 0 y 100", null);
        }

        if ($oldMedio->getDr() !== null && ($oldMedio->getDr() < 0 || $oldMedio->getDr() > 100)) {
            return new DtoResponse("ERROR", "El DR debe estar entre 0 y 100", null);
        }

        $enlacesPermitidos = ['dofollow', 'nofollow', 'sponsored'];

        $tipos = $oldMedio->getTiposEnlacePermitidos();

        if (empty($tipos)) {
            return new DtoResponse("ERROR", "Debe indicar al menos un tipo de enlace permitido", null);
        }

        foreach ($tipos as $tipo) {
            if (!in_array($tipo, $enlacesPermitidos)) {
                return new DtoResponse("ERROR", "Tipo de enlace no válido: $tipo", null);
            }
        }
        

        $medioUp = $this->medioRep->findOneBy(['id' => $id]);

        if(!$medioUp || $medioUp->getId() === null)
        {
            return new DtoResponse("ERROR", "No existe un Medio con este id", null); 
        }

        
        $medioUp->setNombre($oldMedio->getNombre())
                  ->setDominio($oldMedio->getDominio())
                  ->setCategoria($oldMedio->getCategoria())
                  ->setTematicasDelicadas($oldMedio->getTematicasDelicadas())
                  ->setNumEnlacesPermitidos($oldMedio->getNumEnlacesPermitidos())
                  ->setTiposEnlacePermitidos($oldMedio->getTiposEnlacePermitidos())
                  ->setPublicaPortada($oldMedio->isPublicaPortada())
                  ->setPublicaCategorias($oldMedio->isPublicaCategorias())
                  ->setTematicasNoAceptadas($oldMedio->getTematicasNoAceptadas())
                  ->setIndicaPatrocinado($oldMedio->isIndicaPatrocinado())
                  ->setTraficoMes($oldMedio->getTraficoMes())
                  ->setDa($oldMedio->getDa())
                  ->setDr($oldMedio->getDr());

        try{
            $this->em->flush();
        }catch(UniqueConstraintViolationException  $ex){
            return new DtoResponse("ERROR", "Este medio ya está registrado", null);
        }
        
        return new dtoResponse("SUCCESS", "Medio actualizado", $medioUp->toArray());

        
    }

    public function deleteMedio(int $id) : dtoResponse
    {
        if($id === null){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        $medio = $this->medioRep->findOneBy(['id' => $id]);
        
        if(!$medio || $medio->getId() === null){
            return new dtoResponse("ERROR", "Medio no encontrado", null);
        }

        $this->em->remove($medio);
        $this->em->flush();

        return new dtoResponse("SUCCESS", "Medio eliminado con éxito", $medio->toArray());
    }


    private function normalizarDominio(string $dominio): string
    {
        $dominio = preg_replace('#^https?://#', '', strtolower(trim($dominio)));

        $partes = explode('/', $dominio);
        $dominio = $partes[0];

        if (str_starts_with($dominio, 'www.')) {
            $dominio = substr($dominio, 4);
        }

        return $dominio;
    }




}