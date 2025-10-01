<?php


namespace App\Service;

use App\Entity\Proovedor;
use App\Repository\ProovedorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Model\DtoResponse;

class ProovedorService
{

    private EntityManagerInterface $em;
    private ProovedorRepository $provRep;

    public function __construct(EntityManagerInterface $em, ProovedorRepository $provRep)
    {
        $this->em = $em;
        $this->provRep = $provRep;
    }

    public function saveProovedor(Proovedor $prov): DtoResponse
    {
        if(empty(trim($prov->getContacto())) || empty(trim($prov->getEmpresa())) || empty(trim($prov->getEmail())) || 
         empty(trim($prov->getTelefono())) || empty(trim($prov->getDireccion())) || empty(trim($prov->getCiudad())) || 
         empty(trim($prov->getPais())) || $prov->getCreatedAt() === null){
        
            return new DtoResponse("ERROR", "Rellene los campos faltantes", null);
        }

        if (!filter_var($prov->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return new DtoResponse("ERROR", "Email no válido", null);
        }


        if (!preg_match('/^\+?\d{7,15}$/', $prov->getTelefono())) {
            return new DtoResponse("ERROR", "Teléfono no válido", null);
        }

        if ($prov->getCp() && !preg_match('/^\d{5}$/', $prov->getCp())) {
            return new DtoResponse("ERROR", "Código postal no válido", null);
        }

        try{
            $this->em->persist($prov);
            $this->em->flush();
        }catch(UniqueConstraintViolationException  $ex){
            return new DtoResponse("ERROR", "Este proovedor ya está registrado", null);
        }
        return new DtoResponse("SUCCESS", "Proovedor registrado correctamente", $prov->toArray());
    }

    public function getAllProovedores(): DtoResponse
    {
        $proovedores = $this->provRep->findAll();

        if(!is_array($proovedores)){
            return new DtoResponse("ERROR", "Error al recibir los proovedores", null);
        }

        $arrayProovedores = array_map(function($proovedor){
            return[
            'id' => $proovedor->getId(),
            'contacto' => $proovedor->getContacto(),
            'empresa' => $proovedor->getEmpresa(),
            'email' => $proovedor->getEmail(),
            'telefono' => $proovedor->getTelefono(),
            'direccion' => $proovedor->getDireccion(),
            'cp' => $proovedor->getCp(),
            'ciudad' => $proovedor->getCiudad(),
            'provincia' => $proovedor->getProvincia(),
            'pais' => $proovedor->getPais(),
            'created_at' => $proovedor->getCreatedAt()?->format('Y-m-d H:i:s'),
            'contenidos' => array_map(function($content){
                return[
                    'id' => $content->getId(),
                    'titulo' => $content->getTitulo(),
                    'description' => $content->getDescription(),
                    'pago_proveedor' => $content->getPagoProveedor()
                ];
            }, $proovedor->getContenidos()->toArray())

        ];
        }, $proovedores);

        return new DtoResponse("SUCCESS", "Proovedores disponibles", $arrayProovedores);   
    }

    public function getProovedoresbyEmpresa(string $empresa): DtoResponse
    {

        if(empty(trim($empresa))){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        $proovedores = $this->provRep->findBy(['empresa' => $empresa]);

        if(empty($proovedores)){
            return new DtoResponse("ERROR", "No hay proovedores con ese nombre", null);
        }

       $arrayProovedores = array_map(function($proovedor){
            return[
            'id' => $proovedor->getId(),
            'contacto' => $proovedor->getContacto(),
            'empresa' => $proovedor->getEmpresa(),
            'email' => $proovedor->getEmail(),
            'telefono' => $proovedor->getTelefono(),
            'direccion' => $proovedor->getDireccion(),
            'cp' => $proovedor->getCp(),
            'ciudad' => $proovedor->getCiudad(),
            'provincia' => $proovedor->getProvincia(),
            'pais' => $proovedor->getPais(),
            'created_at' => $proovedor->getCreatedAt()?->format('Y-m-d H:i:s'),
            'contenidos' => array_map(function($content){
                return[
                    'id' => $content->getId(),
                    'titulo' => $content->getTitulo(),
                    'description' => $content->getDescription(),
                    'pago_proveedor' => $content->getPagoProveedor()
                ];
            }, $proovedor->getContenidos()->toArray())

        ];
        }, $proovedores);

        return new DtoResponse("SUCCESS", "Proovedores por empresa encontrados", $arrayProovedores);   
    }

    public function getProovedoresbyCiudad(string $ciudad): DtoResponse
    {

        if(empty(trim($ciudad))){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        $proovedores = $this->provRep->findBy(['ciudad' => $ciudad]);

        if(empty($proovedores)){
            return new DtoResponse("ERROR", "No hay proovedores con esa ciudad localizada", null);
        }

       $arrayProovedores = array_map(function($proovedor){
            return[
            'id' => $proovedor->getId(),
            'contacto' => $proovedor->getContacto(),
            'empresa' => $proovedor->getEmpresa(),
            'email' => $proovedor->getEmail(),
            'telefono' => $proovedor->getTelefono(),
            'direccion' => $proovedor->getDireccion(),
            'cp' => $proovedor->getCp(),
            'ciudad' => $proovedor->getCiudad(),
            'provincia' => $proovedor->getProvincia(),
            'pais' => $proovedor->getPais(),
            'created_at' => $proovedor->getCreatedAt()?->format('Y-m-d H:i:s'),
            'contenidos' => array_map(function($content){
                return[
                    'id' => $content->getId(),
                    'titulo' => $content->getTitulo(),
                    'description' => $content->getDescription(),
                    'pago_proveedor' => $content->getPagoProveedor()
                ];
            }, $proovedor->getContenidos()->toArray())

        ];
        }, $proovedores);

        return new DtoResponse("SUCCESS", "Proovedores por empresa encontrados", $arrayProovedores);   
    }

    public function getProovedoresbyEmail(string $email): DtoResponse
    {

        if(empty(trim($email))){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        $proovedor = $this->provRep->findOneBy(['email' => $email]);

        if(!$proovedor || $proovedor->getId() === null){
            return new DtoResponse("ERROR", "No hay proovedor con este email", null);
        }

        return new DtoResponse("SUCCESS", "Proovedor encontrado", $proovedor->toArray());   

    }
   

    public function updateProovedorById(int $id, Proovedor $oldProv)
    {
       if(empty($id)){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        if(empty(trim($oldProv->getContacto())) || empty(trim($oldProv->getEmpresa())) || empty(trim($oldProv->getEmail())) || 
         empty(trim($oldProv->getTelefono())) || empty(trim($oldProv->getDireccion())) || empty(trim($oldProv->getCiudad())) || 
         empty(trim($oldProv->getPais())) || $oldProv->getCreatedAt() === null){
        
            return new DtoResponse("ERROR", "Rellene los campos faltantes", null);
        }

        if (!filter_var($oldProv->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return new DtoResponse("ERROR", "Email no válido", null);
        }


        if (!preg_match('/^\+?\d{7,15}$/', $oldProv->getTelefono())) {
            return new DtoResponse("ERROR", "Teléfono no válido", null);
        }

        if ($oldProv->getCp() && !preg_match('/^\d{5}$/', $oldProv->getCp())) {
            return new DtoResponse("ERROR", "Código postal no válido", null);
        }

    
        $proovedorUp = $this->provRep->findOneBy(['id' => $id]);

        if(!$proovedorUp || $proovedorUp->getId() === null)
        {
            return new DtoResponse("ERROR", "No existe un proovedor con este id", null); 
        }

        $proovedorUp->setContacto($oldProv->getContacto())
                  ->setEmpresa($oldProv->getEmpresa())
                  ->setEmail($oldProv->getEmail())
                  ->setTelefono($oldProv->getTelefono())
                  ->setDireccion($oldProv->getDireccion())
                  ->setCp($oldProv->getCp())
                  ->setCiudad($oldProv->getCiudad())
                  ->setProvincia($oldProv->getProvincia())
                  ->setPais($oldProv->getPais());

        try{
            $this->em->flush();
        }catch(UniqueConstraintViolationException  $ex){
            return new DtoResponse("ERROR", "Este proovedor ya está registrado", null);
        }
        
        return new dtoResponse("SUCCESS", "Proovedor actualizado", $proovedorUp->toArray());
        
    }


    public function deleteProovedor(int $id) : dtoResponse
    {
        if(empty($id)){
            return new DtoResponse("ERROR", "Rellene el campo faltante", null); 
        }

        $proovedor = $this->provRep->findOneBy(['id' => $id]);
        
        if(!$proovedor || $proovedor->getId() === null){
            return new dtoResponse("ERROR", "Proovedor no encontrado", null);
        }

        $this->em->remove($proovedor);
        $this->em->flush();

        return new dtoResponse("SUCCESS", "Proovedor eliminado con éxito", $proovedor->toArray());
    }

}
