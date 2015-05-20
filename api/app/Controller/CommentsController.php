<?php  

App::uses('AppController', 'Controller');

class CommentsController extends AppController {
    public function index()
    {
        $options = $this->Comment->buildOptions($this->params->query);
        $options['order'][] = 'Comment.created DESC';
        if (!empty($this->request->query['bindModel'])) {
            $this->Comment->bindModel(
                $this->request->query['bindModel']
            );
        }
        if (!empty($this->request->query['unbindModel'])) {
            $this->Comment->unbindModel(
                $this->request->query['unbindModel']
            );
        }
        $comments = $this->Comment->find('all', $options);

        if (!empty($options['limit'])) {
            unset($options['limit']);
        }

        $count = $this->Comment->find('count', $options);

        $this->set(array(
            'comments' => $comments,
            'count' => $count,
            '_serialize' => array('comments', 'count')
        ));
    }
    public function add() {
        if (!empty($this->request->data)) {
            $comment = $this->Comment->save($this->request->data);
            $this->Comment->unbindAllExcept(['User']);
            $this->set(array(
                'comment' => $this->Comment->findById($comment['Comment']['id']),
                '_serialize' => array('comment')
            ));
        } else {
            throw new MissingDataException('No comment data provided');
        }
    }
}