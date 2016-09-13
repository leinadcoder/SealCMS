<?php
    namespace seal\core\modules\texts;
    class sitetexts{

        public static function getTextView($view){

            $fiSiteText = $GLOBALS['template']::getPath("texts",
                                                        DIR_SITE_TEXT,"txt");

            try{
                $fiContent = file_get_contents($fiSiteText);
                $jsonContent = json_decode($fiContent,true);

                foreach ($jsonContent as $key => $value) {
                    if($key === $view){
                        return $value;
                    }
                }

            }catch(Exception $e){
                echo $e->getMessage();
            }
        }


        public static function getKeySearch($searchKeys){
            $arsKeys = array();

            foreach ($searchKeys as $key => $value) {
                $arsKeys[] = $key;
            }

            return $arsKeys;
        }

        public static function getValueReplace($searchValues){
            $arsValues = array();

            foreach ($searchValues as $key => $value) {
                $arsValues[] = $value;
            }

            return $arsValues;
        }
    }

?>
