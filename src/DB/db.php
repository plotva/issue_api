<?php
namespace Src\DB;

class db {
/**
* Class db
* 
* Шлюз для работы с таблицей issue
* 
*/
    private $db = null;

    /**
        * Конструктор класса db
        * 
        * Шлюз для работы с таблицей issue
        *
        * @param PDO $db Линк к db
        * @return void  
     */
    public function __construct($db)
    {   
        $this->db = $db;
    }

     /**
        * Метод для возвращения всей таблицы issue
        * 
        *    
        * @return array  
     */
    public function findAll()
    {
        $statement = "
            SELECT 
                *
            FROM
                issue;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
        /**
        * Метод для возвращения строки таблицы issue
        * 
        * Возвращает конкретную строку по признаку id
        * 
        * @param int $id id записи в таблице   
        * @return array  
        */
    public function find($id)
    {
        $statement = "
            SELECT 
                *
            FROM
                issue
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id'=>$id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
        /**
        * Метод для добавления заиси в таблицу issue
        * 
        * Добавляет новую запись в таблицу issue
        * 
        * @param array $input Ассоциативный массив  
        * @return int  
        */
    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO issue 
                (name, user, comment, date_create, date_update)
            VALUES
                (:name, :user, :comment, :date_create, :date_update);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'name' => $input['name'],
                'user'  => $input['user'],
                'comment' => $input['comment'],
                'date_create' => $input['date_create'],
                'date_update' => $input['date_update'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
         /**
        * Метод для обновления заиси в таблице issue
        * 
        * Обновляет запись в таблице issue по признаку id, обновляются только поля user и date_update
        * 
        * @param array $input Ассоциативный массив  
        * @return int  
        */
    public function update($id, Array $input)
    {
        $statement = "
            UPDATE issue
            SET 
                user = :user,
                date_update = :date_update
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => $id,
                'user'  => $input['user'],
                'date_update' => $input['date_update'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
      /**
        * Метод для удаления заиси из таблицы issue
        * 
        * Удаляет запись из таблицы issue по признаку id
        * 
        * @param array $input Ассоциативный массив  
        * @return int  
        */
    public function delete($id)
    {
        $statement = "
            DELETE FROM issue
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}


?>