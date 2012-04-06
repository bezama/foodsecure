<?php


    /**
     * Helper class for XML files
     */
    class ApiXmlParser
    {
        /**
         * The main function for converting to an XML document.
         * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
         *
         * @param array $data
         * @param string $rootNodeName - what you want the root node to be - defaultsto data.
         * @param SimpleXMLElement $xml - should only be used recursively
         * @return string XML
         */
        public static function arrayToXml($data, $rootNodeName = 'fosaMessage', & $xml = null)
        {
            if ($xml === null)
            {
                $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
            }

            foreach ($data as $key => $value)
            {
                $numeric = false;
                // No numeric keys in our xml.
                if (is_numeric($key))
                {
                    $numeric = 1;
                    $key = $rootNodeName;
                }

                // Delete any char not allowed in XML element names.
                $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

                // If there is another array found recrusively call this function.
                if (is_array($value))
                {
                    if (self::isAssoc( $value ) || $numeric)
                    {
                        $node = $xml->addChild( $key );
                    }
                    else
                    {
                        $node = $xml;
                    }
                    // Recrusive call.
                    if ($numeric)
                    {
                        $key = 'anon';
                    }
                    self::arrayToXml($value, $key, $node);
                }
                else
                {
                    // add single node.
                    $value = htmlentities($value);
                    $xml->addChild($key, $value);
                }
            }

            // Format XML, including whitespaces
            $doc = new DOMDocument('1.0');
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xml->asXML());
            $doc->formatOutput = true;
            return $doc->saveXML();
        }

        /**
         * Convert an XML document to a multi dimensional array
         * Pass in an XML document (or SimpleXMLElement object) and this recrusively loops
         * through and builds a representative array
         *
         * @param string $xml - XML document - can optionally be a SimpleXMLElement object
         * @return array ARRAY
         */
        public static function toArray($xml)
        {
            if (is_string( $xml ))
            {
                $xml = new SimpleXMLElement($xml);
            }
            $children = $xml->children();

            if (!$children)
            {
                return (string) $xml;
            }

            $arr = array();
            foreach ($children as $key => $node)
            {
                $node = ArrayToXML::toArray( $node );

                // Support for 'anon' non-associative arrays.
                if ($key == 'anon')
                {
                    $key = count( $arr );
                }

                // If the node is already set, put it into an array.
                if (isset($arr[$key]))
                {
                    if (!is_array($arr[$key]) || $arr[$key][0] == null)
                    {
                        $arr[$key] = array($arr[$key]);
                    }
                    $arr[$key][] = $node;
                }
                else
                {
                    $arr[$key] = $node;
                }
            }
            return $arr;
        }

        /**
        * Determine if a variable is an associative array
        *
        * @param var $array
        * @return boolean
        */
        public static function isAssoc($array)
        {
            $isAssoc = is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array))));
            return $isAssoc;
        }
    }
?>