<?php

namespace Tots\EloFilter;

use Illuminate\Http\Request;
use Tots\EloFilter\Where\FactoryWhere;

class QueryRequest
{
    /**
     *
     * @var Request
     */
    protected $request;
    /**
     *
     * @var integer
     */
    protected $page = 1;
    /**
     *
     * @var integer
     */
    protected $perPage = 50;
    /**
     * Almacena todos los wheres de la query
     * @var array
     */
    protected $wheres = array();

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->processRequest();
    }

    protected function processWheres($wheres)
    {
        $this->wheres = FactoryWhere::createAll($wheres);
    }

    protected function processRequest()
    {
        // Get normal params
        $this->page = $this->request->input('page', 1);
        $this->perPage = $this->request->input('per_page', 50);
        $this->processData();
        $this->processDataString();
    }

    protected function processDataString()
    {
        $data = $this->request->input('filters_string');
        if($data == ''){
            return;
        }

        try {
            $jsonObj = json_decode($data);
        } catch (\Throwable $th) {
            return;
        }

        if(isset($jsonObj->wheres) && is_array($jsonObj->wheres)){
            $this->processWheres($jsonObj->wheres);
        }
    }

    protected function processData()
    {
        $data = $this->request->input('filters');
        if($data == ''){
            return;
        }

        $dataString = base64_decode($data);

        try {
            $jsonObj = json_decode($dataString);
        } catch (\Throwable $th) {
            return;
        }

        if(isset($jsonObj->wheres) && is_array($jsonObj->wheres)){
            $this->processWheres($jsonObj->wheres);
        }
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }
    /**
     *
     * @return array
     */
    public function getWheres()
    {
        return $this->wheres;
    }
}