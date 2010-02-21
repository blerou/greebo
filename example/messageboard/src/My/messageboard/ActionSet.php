<?php

namespace My\messageboard;

/**
 * @property PDO $pdo
 */
class ActionSet extends \greebo\conveniences\ActionSet
{
    function indexAction()
    {
    }

    function listAction()
    {
        $offset = $this->request->param('o', 0);
        $limit = $this->request->param('l', 2);
        $l = $limit + 1;
        $query = "
            SELECT * FROM message ORDER BY created_at DESC LIMIT $offset, $l;
        ";
        $result = $this->pdo->query($query)->fetchAll();
        return $this->sendjson(array(
            'messages' => array_slice($result, 0, $limit),
            'more' => (count($result) > $limit),
            'o' => $offset,
            'l' => $limit,
        ));
    }

    function newformAction()
    {
    }

    function createAction()
    {
        if (!$this->request->method('post')) {
            $this->response->status(403);
            return false;
        }

        $title = strip_tags($this->request->param('title'));
        $body = strip_tags($this->request->param('body'));
        $date = date('Y-m-d H:i:s');

        $query = "
            INSERT INTO message (title, body, created_at) VALUES (?, ?, ?);
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array($title, $body, $date));

        return $this->sendjson(array(
            'title' => $title,
            'body' => $body,
            'created_at' => $date,
        ));
    }
}
