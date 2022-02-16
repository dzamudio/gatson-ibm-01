<?php
namespace Core;
use DOMDocument;
use DOMXpath;
use stdClass;

class Crawl
{
    private $output;
    private $domDocument;
    private bool $domDocumentExistsBool = false;

    function getPage($url): bool
    {
        // create curl resource
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->output = curl_exec($ch);
        curl_close($ch);
        //print($this->output);

        return !is_null($this->output);
    }

    function convertOutputToDOM(): bool
    {
        // check if output has data
        if ( is_null($this->output) ){ return false; }

        $this->domDocument = new DOMDocument();
        $this->domDocumentExistsBool = (bool)$this->domDocument->loadHTML($this->output);
        $this->domDocument->preserveWhiteSpace = false;
        //echo $this->domDocument->saveHTML();
        //var_dump($this->domDocument);

        return $this->domDocumentExistsBool;

    }

    function getPageTitle(): bool
    {
        // check if DOM model was created
        if ( is_null($this->domDocumentExistsBool) ){ return false; }

        $titles = $this->domDocument->getElementsByTagName('title');
        foreach ($titles as $title) {
            return $title->nodeValue;
            break;
        }

        return false;
    }

    /**
     *
     * We need to determine what constitutes a "section"
     * - is it a body of text preceding a major heading?
     * @return boolean
     */
    function findSection(): bool
    {
        // check if DOM model was created
        if ( is_null($this->domDocumentExistsBool) ){ return false; }

        // search for H1,H2,H3,H4,H5,H6
        $H1s = $this->domDocument->getElementsByTagName('h1');
        $headings = array();
        foreach ($H1s as $H1) {
            $headings[] = array($H1->nodeName => $H1->nodeValue);
        }

        //print("\n".__LINE__."\n");
        //var_dump(json_encode($headings));

        $Ps = $this->domDocument->getElementsByTagName('p');

        print("\n---- Ps ----\n");
        //var_dump(json_encode($Ps));

        $allElements = $this->domDocument->getElementsByTagName('*');
        // @todo Explore xPath searches and regex. also see if it's possible to do a "find nearest sibling or child"
        foreach ($allElements as $node) {
            echo $node->getNodePath() . "\n";
        }


        return false;

    }

    function searchXPath()
    {
        // check if DOM model was created
        if ( is_null($this->domDocumentExistsBool) ){ return false; }

        // @todo See this for XPATH Syntax: https://www.w3schools.com/xml/xpath_syntax.asp


        return false;
    }

    function diagnostics()
    {
        //$this->getPage('test-search-result-google.html');
        $this->getPage('https://www.php.net/docs.php');
        $this->convertOutputToDOM(); // @todo should this be an internally called method? when would I NOT call this?
        //print("page title: " . $this->getPageTitle() . "\n");
        $this->findSection();
        print("\Core->Crawl->diagnostics: findSection(): " . $this->findSection() . "\n");
        // @todo resolve case sensitivity in getElementsByTagName

    }
}