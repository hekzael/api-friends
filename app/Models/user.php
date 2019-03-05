<?php
namespace App\Models;
use App\Lib\DataBase;
use PDO;

class UserModel
{
    private $db;
    private $table = 'users';
    private $response;

    public function __CONSTRUCT()
    {
        $db = new DataBase();
        $this->db = $db->connect();
        $this->dbStructure = $db->getStructureTable($this->table);

    }

    public function getAll()
    {
        $stm = $this->db->prepare("SELECT * FROM $this->table");
        $stm->execute();
        
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            return $result;    
        }
        return false;
    }

    public function get($id)
    {
        $stm = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stm->execute([$id]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        if($result)
        {
            return $result;
        }
        return false;
    }

    public function create($data) // Crear un recurso nuevo
    {
        $updateData1 = '';
        $updateData2 = '';
        $signos = '';
        foreach($data as $key => $value){
            $updateData1 .= $key.'=? && '; // values para el select
            $updateData2 .= $key.', '; // values para el insert
            $valueData[] = $value; // values para ambos
            $signos .= '?,';
        }
        $updateData1 = substr($updateData1, 0, -3); // subtrae los 3 ultimos caracteres de la cadena
        $updateData2 = substr($updateData2, 0, -2); // subtrae 2 caracteres de la cadena
        $signos = substr($signos, 0, -1); // subtrae 1 caracter de la cadena

        $stm = $this->db->prepare("SELECT * FROM $this->table WHERE $updateData1");
        $stm->execute($valueData);
        $result = $stm->fetch(PDO::FETCH_ASSOC); // consulta si existe
        if(!$result)
        {
            $sql = "INSERT INTO $this->table ($updateData2) VALUES ($signos)";
            $this->db->prepare($sql)->execute($valueData); 

            echo "creado exitosamente";
        }else
        {
            echo ("ya existe");
        }
    }

    public function delete($id)
    {  
        $stm = $this->db->prepare("SELECT * FROM $this->table WHERE id=?");
        $stm->execute(array($id));
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        if($result)
        {
            $stm = $this->db->prepare("DELETE FROM $this->table WHERE id = ?");                   
            $stm->execute(array($id));
                echo "eliminado exitosamente";
        }else
        {
            echo ("no existe");
        }
    }

    public function update($data,$id) // actualiza un recurso, NO ES RECOMENDABLE QUE CREE EL RECURSO!!!!!!
    {
        $updateData = '';
        foreach($this->dbStructure as $value){
            if($value['Field'] == 'ID'){
                continue;
            }
            $updateData .= $value['Field'].'=?,';
            $valueData[] = (isset($data[$value['Field']])) ? $data[$value['Field']] : '';
        }

        $updateData = substr($updateData, 0, -1); // subtrae el ultimo caracter de la cadena
        $valueData[] = $id;
        $stm = $this->db->prepare("UPDATE $this->table SET $updateData WHERE id=?");
        $stm->execute($valueData);
            
    }

    public function replace($data, $id) //actualiza algun campo 
    {
            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE id=?");
            $stm->execute(array($id));
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $updateData = '';
            if($result) // si existe actualiza el campo especifico
            {
                foreach($data as $key => $value){
                    $updateData .= $key.'=?,';
                    $valueData[] = $value;
                }
                $updateData = substr($updateData, 0, -1); // subtrae el ultimo caracter de la cadena
                $valueData[] = $id;
                $stm = $this->db->prepare("UPDATE $this->table SET $updateData WHERE id=?");
                $stm->execute($valueData);
                echo "campo actualizado correctamente";
            }else 
            {
                echo "no existe";
            }
    }


/*     public function get($esp)
    { */
       /*  $esp = 5; */
        /* $result = array("led1"=>"ON","Led2"=>"OFF"); */
       /*  print_r($result); */
/*         if($result)
        {
            return $result;
        }
        return false;
    } */
}