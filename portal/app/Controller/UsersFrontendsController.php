<?php
 App::uses('CakeTime', 'Utility');
 App::uses('AppController', 'Controller');

 class UsersFrontendsController extends AppController {

     public $uses = ['UsersFrontend'];

     public function index() {
         $users = $this->UsersFrontend->find('all', ["conditions" => ['UsersFrontend.group  IS NULL']]);
         $sources = ConnectionManager::sourceList();
        foreach ($sources as $source) {
            $db = ConnectionManager::getDataSource($source);
            if (!method_exists($db, 'getLog')) {
                continue;
            }

            $logInfo = $db->getLog();
            $text = $logInfo['count'] > 1 ? 'queries' : 'query';
            $method = env('REQUEST_METHOD');

            $message =
                "$method {$this->params->url} executed {$logInfo['count']} $text " .
                "on \"$source\" datasource (took {$logInfo['time']} s) \n";

            foreach ($logInfo['log'] as $i => $logEntry) {
                $idx = $i + 1;
                $message .= "  [$idx] {$logEntry['query']}";
                if (isset($logEntry['error'])) {
                    $message .= " - error: {$logEntry['error']}";
                } else {
                    $message .= " - affected {$logEntry['affected']}, " .
                        " numrows: {$logEntry['numRows']}, " .
                        " took: {$logEntry['took']} ms";
                }
            }

            $message .= "\n";
            $this->log($message, 'query');
        }
         $this->set('users', $users);
     }

     public function delete(){
        $this->response->type('json');
        if($this->request->is('post')){
            $resp = $this->UsersFrontend->delete($this->request->data('id'));
        }else{
            $resp = 'Method not valid';
        }
        $this->response->body(json_encode($resp));
        return $this->response;
     }

     public function edit(){
        $this->response->type('json');
        if($this->request->is('post')){
            $request = $this->request->data;
            $resp['data'] = $this->UsersFrontend->save($this->request->data);

            if(!$resp['data']){
                $resp['message'] = 'Error';
            }
        }else{
            $resp['message'] = 'Method not valid';
        }
        $this->response->body(json_encode($resp));
        return $this->response;
     }

     public function checkExistEmail(){
        $this->response->type('json');
        $resp = [];
        $check = $this->UsersFrontend->find('count', ['conditions' =>
            [
                'email' => $this->request->data('email'),
                'id !=' => $this->request->data('id')
            ]
        ]);
        $resp['check'] = $check;
        $this->response->body(json_encode($resp));
        return $this->response;
     }
 }
