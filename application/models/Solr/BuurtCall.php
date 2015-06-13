<?php

class Application_Model_Solr_BuurtCall
{
    private $solrClient;

    private $config = array();

    public function __construct()
    {
        $string = file_get_contents(realpath(APPLICATION_PATH . '/../credentials.json'));
        $credentials = json_decode($string, true);
        $this->config["hostname"] = $credentials['solrHost'];
        $this->config["port"] = $credentials['solrPort'];
        $this->config["path"] = $credentials['solrPath'];

        $this->solrClient = new SolrClient($this->config);
    }

    /**
     * Get buurt
     *
     * @param string $name
     * @return json mixed
     */
    public function getBuurt($input)
    {
        if (strlen($input) == 10 && is_numeric(substr($input, -1, 1))) {
            $val = "id";
        } else {
            $val = "name";
        }
        $query = new SolrQuery();
        $query->setQuery("$val:$input");
        $query->addField('id')->addField('name')->addField('polygon')->addField('aangrenzende');
        $query_response = $this->solrClient->query($query);
        $response = $query_response->getResponse()->response;
        if ($response->numFound === 0) {
            return false;
        }
        return $response;
    }

    /**
     * Get Polygon
     *
     * @param string $name
     * @return array polygon
     */
    public function getPolygon($name)
    {
        if (!$this->getBuurt($name)) {
            return false;
        }
        return $this->getBuurt($name)->docs[0]->polygon;
    }

    /**
     * Get Aangrenzende Buurten
     *
     * @param string $name
     * @return array aangrenzende
     */
    public function getAangrenzendeBuurten($name)
    {
        if (!$this->getBuurt($name)) {
            return false;
        }
        return $this->getBuurt($name)->docs[0]->aangrenzende;
    }

    /**
     * Get Name
     *
     * @param string $name
     * @return string name
     */
    public function getName($name)
    {
        if (!$this->getBuurt($name)) {
            return false;
        }
        return $this->getBuurt($name)->docs[0]->name[0];
    }

    /**
     * Get ID
     *
     * @param string $name
     * @return string id
     */
    public function getId($name)
    {
        if (!$this->getBuurt($name)) {
            return false;
        }
        return $this->getBuurt($name)->docs[0]->id;
    }
}