<?php

namespace app\core;

use Exception;
use app\core\database\Database;

class Application {
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    protected array $eventListeners = [];

    public static Application $app;
    public static string $ROOT_DIR;
    public string $userClass;
    public string $layout = 'main';
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public Database $db;
    public Session $session;
    public View $view;
    public ?UserModel $user;

    public function __construct($rootDir, $config) {
        $this->user = null;
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['database']);
        $this->session = new Session();
        $this->view = new View();

        $userId = Application::$app->session->get('userId');
        if ($userId) {
            $key = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$key => $userId]);
        }
    }

    public static function isGuest(): bool {
        return !self::$app->user;
    }

    public function login(UserModel $user): true {
        $this->user = $user;
        $className = get_class($user);
        $primaryKey = $className::primaryKey();
        Application::$app->session->set('userId', $user->{$primaryKey});

        return true;
    }

    public function logout(): void {
        $this->user = null;
        self::$app->session->remove('userId');
    }

    public function run(): void {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        } catch (Exception $e) {
            echo $this->router->renderView('_error', [
                'exception' => $e,
            ]);
        }
    }

    public function triggerEvent($eventName): void {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    public function on($eventName, $callback): void {
        $this->eventListeners[$eventName][] = $callback;
    }
}