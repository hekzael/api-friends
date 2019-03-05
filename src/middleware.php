<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use \Tuupola\Middleware\HttpBasicAuthentication;

$container = $app->getContainer();

$container["jwt"] = new StdClass;
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "secret" => "123456789helo_secret",
    "rules" => [
        new \Tuupola\Middleware\JwtAuthentication\RequestPathRule([
            "path" => ["/auth","/user"],
            "ignore" => ["/auth/login"]
        ]),
        new \Tuupola\Middleware\JwtAuthentication\RequestMethodRule([
            "ignore" => ["OPTIONS"]
        ]),
    ],
    "before" => function ($response, $params) {
        $this->container['jwt'] = $params['decoded']['sub'];
    },
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
], $container));

