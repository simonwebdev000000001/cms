<?php
class IController
{
    public function __construct(){

    }
    public static function archive()
    {
        $results = array();
        $data = IController::getList();
        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "IController Archive | Widget News";
        require(TEMPLATE_PATH . "/archive.php");
    }

    public static function viewArticle()
    {
        if (!isset($_GET["articleId"]) || !$_GET["articleId"]) {
            homepage();
            return;
        }

        $results = array();
        $results['article'] = IController::getById((int)$_GET["articleId"]);
        $results['pageTitle'] = $results['article']->title . " | Widget News";
        require(TEMPLATE_PATH . "/viewArticle.php");
    }

    public static function homepage()
    {
        $results = array();
        $data = IController::getList(HOMEPAGE_NUM_ARTICLES);
        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "Widget News";
        require(TEMPLATE_PATH . "/homepage.php");
    }
}