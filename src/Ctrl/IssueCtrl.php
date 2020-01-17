<?php
namespace Src\Ctrl;

use Src\DB\db;

class IssueCtrl {
/**
* Class IssueCtrl
* 
* Контроллер для работы со шлюзом таблицы issue
* 
* @var obj $link Ликнк к БД
* @var string $requestMethod Тип полученного HTTP запроса
* @var int $issueid id записи в таблице issue
* @var obj $db оъект класса db
*/
    private $link;
    private $requestMethod;

    private $issueid;
    private $db;

    /**
        * Конструктор класса IssueCtrl
        * 
        * Приявязка переменных к объекту класса 
        *
        * @return void  
     */

    public function __construct($link, $requestMethod,$issueid)
    {
        $this->link = $link;
        $this->requestMethod = $requestMethod;
        $this->issueid = $issueid;
        $this->db = new db($link);
    }

    /**
        * Метод для работы с http запросами
        * 
        *    
        * @return string Возвращает json строку с результатом запроса  
     */

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->issueid) {
                    $response = $this->getIssue($this->issueid);
                } else {
                    $response = $this->getAllIssue();
                };
                break;
            case 'POST':
                $response = $this->createIssueFromRequest();
                break;
            case 'PUT':
                $response = $this->updateIssueFromRequest($this->issueid);
                break;
            case 'DELETE':
                $response = $this->deleteIssue($this->issueid);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo json_encode ($response['body']);
        }
    }

     /**
        * Метод обработчик для типа запроса GET
        * 
        *    
        * @return array 
     */
    private function getAllIssue()
    {
        $result = $this->db->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

        /**
        * Метод обработчик для типа запроса GET
        * 
        * @param int $id id записи в таблице issue 
        * @return array 
        */ 
    private function getIssue($id)
    {
        $result = $this->db->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

        /**
        * Метод обработчик для типа запроса POST
        * 
        * @var string  $date_create  Текущая дата
        * @var array  $input Данные из http запроса
        * @return array 
        */ 
    private function createIssueFromRequest()
    {   $date_=date("Y-m-d H:i:s");
        $input = (array) json_decode (file_get_contents('php://input'), TRUE);
         $input['date_create']=$date_;
         $input['date_update']=$date_;
        
        if (! $this->validateinsertIssue($input)) {
            return $this->unprocessableResponse($input);
        }
        $this->db->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

       /**
        * Метод обработчик для типа запроса PUT
        * 
        * @var array  $input Данные из http запроса
        * @return array 
        */

    private function updateIssueFromRequest($id)
    {     
        $result = $this->db->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'),TRUE);
        $input['date_update']=date("Y-m-d H:i:s");
        if (! $this->validateupdateIssue($input)) {
            return $this->unprocessableResponse($input);
        }
        $this->db->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }
        /**
        * Метод обработчик для типа запроса DELETE
        * 
        * @param int $id id записи в таблице issue
        * @return int 
        */
    private function deleteIssue($id)
    {
        $result = $this->db->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->db->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

         /**
        * Метод валидатор данных
        * 
        * @param array $input Массив поступивших данных
        * @return bool 
        */

    private function validateinsertIssue($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['user'])) {
            return false;
        }
        return true;
    }
      /**
        * Метод валидатор данных
        * 
        * @param array $input Массив поступивших данных
        * @return bool 
        */
        private function validateupdateIssue($input)
    {
        if (! isset($input['user'])) {
            return false;
        }
        return true;
    }

      /**
        * Метод для формирования ответа на неверную валидацию  данных
        * 
        * @param array $data Массив поступивших данных
        * @return string 
        */

    private function unprocessableResponse($data)
    {    
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input',
            'data' => $data,
            'name' => $data['name']
        ]);
             return $response;
    }

    /**
        * Метод для формирования ответа по умолчаению
        * 
        * 
        * @return string 
        */
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}

?>