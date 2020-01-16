<?php
namespace Src\Ctrl;

use Src\DB\db;

class IssueCtrl {

    private $link;
    private $requestMethod;

    private $issueid;
    private $db;

    public function __construct($link, $requestMethod,$issueid)
    {
        $this->link = $link;
        $this->requestMethod = $requestMethod;
        $this->issueid = $issueid;
        $this->db = new db($link);
    }

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

    private function getAllIssue()
    {
        $result = $this->db->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

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

    private function createIssueFromRequest()
    {   $date_create=date("Y-m-d H:i:s");
        $input = (array) json_decode (file_get_contents('php://input'), TRUE);
         $input['date_create']=$date_create;
         $input['date_update']=$date_create;
        
        if (! $this->validateinsertIssue($input)) {
            return $this->unprocessableEntityResponse($input);
        }
        $this->db->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateIssueFromRequest($id)
    {     
        $result = $this->db->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'),TRUE);
        $input['date_update']=date("Y-m-d H:i:s");
        if (! $this->validateupdateIssue($input)) {
            return $this->unprocessableEntityResponse($input);
        }
        $this->db->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

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

        private function validateupdateIssue($input)
    {
        if (! isset($input['user'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse($data)
    {    
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input',
            'data' => $data,
            'name' => $data['name']
        ]);
             return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}

?>