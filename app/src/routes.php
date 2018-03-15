<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/messages', function (Request $request, Response $response): Response {
    $params = $request->getParams();
    foreach (['email', 'name', 'comment'] as $requiredField) {
        if (empty($params[$requiredField])) {
            return $response->withStatus(400, sprintf('Field %s is mandatory', $requiredField));
        }
    }

    if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
        return $response->withStatus(400, 'Invalid email address');
    }

    $this->guestbookModel->create($params);

    return $response->withStatus(204);
});

$app->get('/messages', function (Request $request, Response $response): Response {
    return $response->withJson(
        $this->guestbookModel->getLastMessages(),
        200
    );
});
