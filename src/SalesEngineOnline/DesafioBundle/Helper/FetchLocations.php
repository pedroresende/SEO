<?php

namespace SalesEngineOnline\DesafioBundle\Helper;

/**
 * Description of FetchLocations
 *
 * @author Pedro Resende <pedroresende@mail.resende.biz>
 */
class FetchLocations {
    /*
     * Function responsible for fetching the client's locations
     */
    public function fetch() {
        $document = new \DOMDocument();
        $document->loadXml(file_get_contents('http://clientes.salesengineonline.com/teste_recrutamento_programador/index.php'));
        xml_parse_into_struct(xml_parser_create(), $document->saveXML(), $vals, $index);

        $locations = array();
        for ($i = 1; $i < sizeof($vals) - 1; $i++) {
            array_push($locations, array($vals[$i]['attributes']['ID'], $vals[$i]['attributes']['VALUE']));
        }
        return $locations;
    }

}
