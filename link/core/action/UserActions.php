<?php

class UserActions
{
    private $user;

    public function __construct()
    {
        session_start();
    }

    public function login($lgn, $psw)
    {
        $results = array();
        $results['pageTitle'] = "Admin Login | Widget News";
        if (($this->checkData($lgn, md5($psw)))) {
            $_SESSION['username'] = $lgn;
            header("Location: index.php");
        } else {
            $results['errorMessage'] = "Incorrect username or password. Please try again.";
            header('Location:site.ua/');
        }
    }

    private function checkData($lgn, $psw)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = 'Select * from users WHERE login = :lgn and  password=:psw';
        $st = $conn->prepare($sql);
        $st->bindValue(":lgn", $lgn, ":psw", $psw, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn=null;
        return $this->getUser($row);
    }

    private function getUser($row)
    {
        if (isset($row)) {
            $this->user = new User();
            $this->user->setAdress($row['adress']);
            $this->user->setFio($row['fio']);
            $this->user->setPhone($row['phone']);
            $this->user->setLogin($row['login']);
            $this->user->setEmail($row['email']);

        }
        return $this->user;
    }

    public function logout()
    {
        unset($_SESSION['username']);
        header("Location: index.php");
    }
    public function regUser($data){
        try {
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = 'Insert into users VALUES ($data[\'fio\'],$data[\'login\'],
$data[\'password\'],$data[\'phone\'],$data[\'adress\'],$data[\'email\'])';
            $st = $conn->prepare($sql);
            $st->execute();
        }catch (Exception $e){
            echo 'exception'.$e;
        }
        finally{ $conn = null;}
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