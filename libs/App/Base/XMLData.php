<?php

class XMLDtat implements FileData
{
  private $data;
  private $dataDirectory;

  public function __construct()
  {
    $this->dataDirectory = DATA_DIRECTORY;
    $this->data = $this->getData();
  }
  public function getData()
  {
    return array();
  }
  public function saveData()
  {
    $this->data = array();
  }
}


/*

function generate_xml_element( $dom, $data ) {
    if ( empty( $data['name'] ) )
        return false;

    // Create the element
    $element_value = ( ! empty( $data['value'] ) ) ? $data['value'] : null;
    $element = $dom->createElement( $data['name'], $element_value );

    // Add any attributes
    if ( ! empty( $data['attributes'] ) && is_array( $data['attributes'] ) ) {
        foreach ( $data['attributes'] as $attribute_key => $attribute_value ) {
            $element->setAttribute( $attribute_key, $attribute_value );
        }
    }

    // Any other items in the data array should be child elements
    foreach ( $data as $data_key => $child_data ) {
        if ( ! is_numeric( $data_key ) )
            continue;

        $child = generate_xml_element( $dom, $child_data );
        if ( $child )
            $element->appendChild( $child );
    }

    return $element;
}

$doc = new DOMDocument();
$child = generate_xml_element( $doc, $data );
if ( $child )
    $doc->appendChild( $child );
$doc->formatOutput = true; // Add whitespace to make easier to read XML
$xml = $doc->saveXML();


+++++++++++++++++++++++++++++++++++

<?php
// initializing or creating array
$student_info = array(your array data);

// creating object of SimpleXMLElement
$xml_student_info = new SimpleXMLElement("<?xml version=\"1.0\"?><student_info></student_info>");

// function call to convert array to xml
array_to_xml($student_info,$xml_student_info);

//saving generated xml file
$xml_student_info->asXML('file path and name');


// function defination to convert array to xml
function array_to_xml($student_info, &$xml_student_info) {
    foreach($student_info as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_student_info->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                $subnode = $xml_student_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }
        else {
            $xml_student_info->addChild("$key",htmlspecialchars("$value"));
        }
    }
}

?>
*/
