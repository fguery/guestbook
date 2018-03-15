<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/', function (Request $request, Response $response): Response {
    $params = $request->getParams();
    foreach (['email', 'name', 'comment'] as $requiredField) {
        if (empty($params[$requiredField])) {
            return $response->withJson(
                [
                    'error' => sprintf('Field %s is mandatory', $requiredField)
                ],
                400
            );
        }
    }

    if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
        return $response->withJson(
            [
                'error' => 'Invalid email address'
            ],
            400
        );
    }

    $this->guestbookModel->create($params);

    return $response->withStatus(204);
});

$app->get('/', function (Request $request, Response $response): Response {
    return $response->withJson(
        $this->guestbookModel->getLastMessages(),
        200
    );
});
