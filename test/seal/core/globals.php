<?php
    spl_autoload_register();

    define("DIR_ROOT", getcwd());
    define("DS", DIRECTORY_SEPARATOR);
    define("DIR_SITE_TEXT", "site_texts");
    define("DIR_MODEL_VIEWS", "model_views");
    define("DIR_MASTER_PAGE", "master_pages");
    define("DIR_MODEL_VIEWS_CONSTANT", "model_views" . DS . "constant");

    define("SREGEXPVIEWS_CONSTANT", "/\{|view\:[a-zA-Z]{1,}|\}/");
    define("SREGEXPVIEWS_DINAMYC", "/^\{view\d\:|.{1,}/");

    $template = 'seal\core\modules\view\Template';
    $texts    = 'seal\core\modules\texts\sitetexts';

?>
