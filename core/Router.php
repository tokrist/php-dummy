<?php

namespace app\core;

class Router {
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback): void {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback): void {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * @throws exceptions\PathNotFoundException
     */
    public function resolve() {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if(!$callback) {
            $this->response->setStatusCode(404);
            throw new \app\core\exceptions\PathNotFoundException();
        }

        if(is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if(is_array($callback)) {
            /** @var Controller $controller */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }

            $callback[0] = $controller;
        }

        return call_user_func($callback, $this->request, $this->response);
    }

}