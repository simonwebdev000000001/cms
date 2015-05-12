<?php

require("config.php");


class UserActions
{
    protected $action = null;
    protected $username = null;

    public function __construct()
    {
        session_start();
        $this->action = isset($_GET['action']) ? $_GET['action'] : "";
        $this->username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
        $this->chooseWay($this->action, $this->username);
    }

    private function chooseWay($action, $username)
    {
        if ($action != "login" && $action != "logout" && !$username) {
            $this->login();
            return;
        } else if (isset($_POST['SignIn'])) {
            $this->userCheck($this->action);
        } else {
            $this->adminCheck($this->action);
        }
    }

    private function adminCheck($action)
    {
        switch ($action) {
            case 'login':
                $this->login();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'newArticle':
                $this->newArticle();
                break;
            case 'editArticle':
                $this->editArticle();
                break;
            case 'deleteArticle':
                $this->deleteArticle();
                break;
            default:
                $this->listArticles();
        }
    }

    private function userCheck($action)
    {
        switch ($action) {
            case 'login':
                $this->login($_POST['username'], $_POST['password']);
                break;
            case 'logout':
                $this->logout();
                break;
            default:
                $this->listArticles();
        }
    }

    private function login()
    {
        $numargs = func_num_args();
        $results = array();
        $results['pageTitle'] = "Admin Login | Widget News";
        if ($numargs > 0) {
            if ($this->checkData($numargs[0], md5($numargs[1]))) {
                $_SESSION['username'] = $numargs[0];
                header("Location: index.php");
            } else {
                $results['errorMessage'] = "Incorrect username or password. Please try again.";
                header('Location:site.ua/');
            }
        } else {
            if (isset($_POST['login'])) {
                if ($_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD) {
                    $_SESSION['username'] = ADMIN_USERNAME;
                    header("Location: index.php");
                } else {
                    $results['errorMessage'] = "Incorrect username or password. Please try again.";
                    require(TEMPLATE_PATH . "/admin/loginForm.php");
                }
            } else {
                require(TEMPLATE_PATH . "/admin/loginForm.php");
            }
        }

    }


    private function checkData($lgn, $psw)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = 'Select login from users WHERE login = :lgn UNION all select password from users where password=:psw';
        $st = $conn->prepare($sql);
        $st->bindValue(":lgn", $lgn, ":psw", $psw, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        return isset($row);
    }

    private function logout()
    {
        unset($_SESSION['username']);
        header("Location: index.php");
    }

    public function newArticle()
    {
        $results = array();
        $results['pageTitle'] = "New Article";
        $results['formAction'] = "newArticle";

        if (isset($_POST['saveChanges'])) {
            $article = new IController;
            $article->storeFormValues($_POST);
            $article->insert();
            header("Location: index.php?status=changesSaved");

        } elseif (isset($_POST['cancel'])) {
            header("Location: index.php");
        } else {
            $results['article'] = new IController;
            require(TEMPLATE_PATH . "/admin/editArticle.php");
        }

    }

    public function editArticle()
    {
        $results = array();
        $results['pageTitle'] = "Edit Article";
        $results['formAction'] = "editArticle";

        if (isset($_POST['saveChanges'])) {
            if (!$article = IController::getById((int)$_POST['articleId'])) {
                header("Location: index.php?error=articleNotFound");
                return;
            }
            $article->storeFormValues($_POST);
            $article->update();
            header("Location: index.php?status=changesSaved");
        } elseif (isset($_POST['cancel'])) {
            header("Location: index.php");
        } else {
            $results['article'] = IController::getById((int)$_GET['articleId']);
            require(TEMPLATE_PATH . "/admin/editArticle.php");
        }

    }


    public function deleteArticle()
    {
        if (!$article = IController::getById((int)$_GET['articleId'])) {
            header("Location: index.php?error=articleNotFound");
            return;
        }
        $article->delete();
        header("Location: index.php?status=articleDeleted");
    }


    public function listArticles()
    {
        $results = array();
        $data = IController::getList();
        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "All Articles";
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "articleNotFound") $results['errorMessage'] = "Error: Article not found.";
        }
        if (isset($_GET['status'])) {
            if ($_GET['status'] == "changesSaved") $results['statusMessage'] = "Your changes have been saved.";
            if ($_GET['status'] == "articleDeleted") $results['statusMessage'] = "Article deleted.";
        }

        require(TEMPLATE_PATH . "/admin/listArticles.php");
    }
}

?>
