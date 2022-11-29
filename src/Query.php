<?php

namespace Tots\EloFilter;

use Illuminate\Http\Request;

class Query
{
    /**
     *
     * @var QueryRequest
     */
    protected $request;
    /**
     *
     * @var string
     */
    protected $modelClass;

    public function execute()
    {
        // Create Eloquent Model
        $query = $this->modelClass::select($this->getTable() .'.*');
        // Process All Wheres
        foreach($this->request->getWheres() as $where){
            $where->run($query);
        }

        //return $query->paginate($configure->getLimit(), ['*'], 'page', $configure->getPage())
        return $query->paginate($this->getPerPage(), ['*'], 'page', $this->getPage());
    }

    /**
     * Obtiene el nombre de la tabla del modelo.
     *
     * @return string
     */
    protected function getTable()
    {
        $model = new $this->modelClass;
        return $model->getTable();
    }
    /**
     *
     * @param Request $request
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = new QueryRequest($request);
    }
    /**
     *
     * @return QueryRequest
     */
    public function getQueryRequest()
    {
        return $this->request;
    }
    /**
     *
     * @param string $modelClass
     * @return void
     */
    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
    }
    /**
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->request->getPage();
    }
    /**
     *
     * @return integer
     */
    public function getPerPage()
    {
        return $this->request->getPerPage();
    }

    public static function run($modelName, Request $request)
    {
        $obj = new Query();
        $obj->setModelClass($modelName);
        $obj->setRequest($request);
        return $obj;
    }
}