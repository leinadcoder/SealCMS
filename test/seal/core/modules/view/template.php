<?php
    namespace seal\core\modules\view;
    class template{

        private $fiView;
        private $context;
        private $htmlView;
        private $fiMasterPage;
        private $htmlMasterPage;

        public static function getView($view,$masterPage){
            $context      = self::getContext();
            $fiMasterPage = self::getPath($masterPage, DIR_MASTER_PAGE, "html");

            $arsViewTexts = $GLOBALS['texts']::getTextView($view);

            $arsSearchKeys = $GLOBALS['texts']::getKeySearch($arsViewTexts);
            $arsSearchValues = $GLOBALS['texts']::getValueReplace($arsViewTexts);

            if(file_exists($fiMasterPage) || is_readable($fiMasterPage))
            {
                $fiView = self::getPath($view, DIR_MODEL_VIEWS, "html");

                if(file_exists($fiView) || is_readable($fiView)){
                    $htmlMasterPage = file_get_contents($fiMasterPage,
                                                        false, $context);

                    $htmlView = file_get_contents($fiView, false, $context);

                    $htmlMasterPage = self::getConstantView($htmlMasterPage);

                    $htmlMasterPage = self::getDinamycView($htmlView,
                                                            $htmlMasterPage);

                    $htmlMasterPage = str_replace($arsSearchKeys,
                        $arsSearchValues, $htmlMasterPage);

                    $fiTemp = tempnam(sys_get_temp_dir(), 'sfw');
                    file_put_contents($fiTemp, $htmlMasterPage);

                    include($fiTemp);

                    if ($fiTemp) {
                        unlink($fiTemp);
                    }

                }else{
                    echo "The view you requested was not found.";
                }
            }else{
                echo "The master page you requested was not found.";
            }
        }

        public static function getPath($fiView,$dir,$extFile){

            return DIR_ROOT . DS . $dir . DS . $fiView . "." . $extFile;

        }

        public static function getContext(){
            $arsClose = array("header" => "Connection: close\r\n");
            $context = stream_context_create(array("http" => $arsClose));

            return $context;
        }

        public static function getConstantView($htmlMasterPage){

            $arsReplaces   = array();
            $arsHtmlValues = array();
            $context       = self::getContext();
            $sRegex        = SREGEXPVIEWS_CONSTANT;

            if(preg_match_all ($sRegex, $htmlMasterPage, $sMatches)){

                foreach ($sMatches as $arsContent)
                {
                    foreach ($arsContent as $iKey => $sValue)
                    {
                        $arsPieces = explode(":", $sValue);
                        if(count($arsPieces) > 1)
                        {
                            $htmlConstantView = "";

                            $fiConstantView = self::getPath($arsPieces[1],
                                                     DIR_MODEL_VIEWS_CONSTANT
                                                     ,"html");

                            if(file_exists($fiConstantView) ||
                                is_readable($$fiConstantView))
                            {
                                $htmlConstantView =
                                        trim(file_get_contents(
                                                                $fiConstantView,
                                                                false,
                                                                $context));
                            }

                            $sReplace = "{" . $sValue . "}";

                            $arsReplaces[]   = $sReplace;
                            $arsHtmlValues[] = $htmlConstantView;

                        }
                    }
                }

                $htmlMasterPage = str_replace($arsReplaces, $arsHtmlValues,
                                                $htmlMasterPage);

                return $htmlMasterPage;
            }
        }

        public static function getDinamycView($htmlView,$htmlMasterPage){
            $htmlContent   = "";
            $arsReplaces   = array();
            $arsHtmlValues = array();
            $context       = self::getContext();
            $sRegex        = SREGEXPVIEWS_DINAMYC;

            if (preg_match_all ($sRegex, $htmlView, $sMatches))
            {
                foreach ($sMatches as $arsContent)
                {
                    foreach ($arsContent as $iKey => $sValue)
                    {
                        $arsPieces = explode("{view", $sValue);

                        if(count($arsPieces) < 2)
                        {
                            $htmlContent .= $sValue . PHP_EOL;
                        }else{
                            $arsReplaces[] = trim($sValue);

                            if(!empty($htmlContent))
                                $arsHtmlValues[] = trim($htmlContent);

                            $htmlContent = "";
                        }
                    }
                }

                if(count($arsReplaces) > count($arsHtmlValues))
                    $arsHtmlValues[] = $htmlContent;

                $htmlMasterPage = str_replace($arsReplaces, $arsHtmlValues,
                                                $htmlMasterPage);

                return $htmlMasterPage;
            }
        }
    }

?>
