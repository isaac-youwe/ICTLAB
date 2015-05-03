<?php
class Application_Model_Funda_House {
    private $_adres;
    private $_broncode;
    private $_hoofdFoto;
    private $_straat;
    private $_url;
    private $_huisnummer;
    private $_huisnummerToevoeging;
    private $_id;
    private $_plaats;
    private $_postcode;
    private $_prijs;
    private $_prijsGeformatteerd;
    private $_publicatieDatum;

    function __construct($adres, $broncode, $huisnummer, $huisnummerToevoeging, $id, $plaats, $postcode, $prijs, $prijsGeformateerd, $publicatieDatum, $straat)
    {
        $this->_adres = $adres;
        $this->_broncode = $broncode;
        $this->_huisnummer = $huisnummer;
        $this->_huisnummerToevoeging = $huisnummerToevoeging;
        $this->_id = $id;
        $this->_plaats = $plaats;
        $this->_postcode = $postcode;
        $this->_prijs = $prijs;
        $this->_prijsGeformatteerd = $prijsGeformateerd;
        $this->_publicatieDatum = $publicatieDatum;
        $this->_straat = $straat;
    }

    /**
     * @return mixed
     */
    public function getAdres()
    {
        return $this->_adres;
    }

    /**
     * @return mixed
     */
    public function getBroncode()
    {
        return $this->_broncode;
    }

    /**
     * @return mixed
     */
    public function getHuisnummer()
    {
        return $this->_huisnummer;
    }

    /**
     * @return mixed
     */
    public function getHuisnummerToevoeging()
    {
        return $this->_huisnummerToevoeging;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getPlaats()
    {
        return $this->_plaats;
    }

    /**
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->_postcode;
    }

    /**
     * @return mixed
     */
    public function getPrijs()
    {
        return $this->_prijs;
    }

    /**
     * @return mixed
     */
    public function getPrijsGeformatteerd()
    {
        return $this->_prijsGeformatteerd;
    }

    /**
     * @return mixed
     */
    public function getPublicatieDatum()
    {
        return $this->_publicatieDatum;
    }

    /**
     * @return mixed
     */
    public function getStraat()
    {
        return $this->_straat;
    }


} 