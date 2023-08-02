<?php

namespace Tots\EloFilter;

use Illuminate\Http\Request;
use Tots\EloFilter\Where\AbstractWhere;
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
    /**
     * Almacena todos los joins de la query
     * @var array
     */
    protected $joins = array();
    /**
     * Almacena las relaciones
     * @var array
     */
    protected $withs = array();
    /**
     * Almacena los orders
     * @var array
     */
    protected $orders = array();

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->processRequest();
    }

    /**
     * Agregar un join a la query
     * @param string $key
     * @param mixed $value
     */
    public function addJoin($table, $tableColumn, $tableRelation)
    {
        $this->joins[] = array('table' => $table, 'column_table' => $tableColumn, 'column_relation' => $tableRelation);
    }
    /**
     * Agregar un where a la query
     * @param string $key
     * @param mixed $value
     */
    public function addWhere($key, $value)
    {
        $this->wheres[] = FactoryWhere::create(array(
            'type' => AbstractWhere::TYPE_EQUAL,
            'key' => $key,
            'value' => $value
        ));
    }
    /**
     * Agregar un where a la query
     * @param string $key
     */
    public function addWhereNull($key)
    {
        $this->wheres[] = FactoryWhere::create(array(
            'type' => AbstractWhere::TYPE_NULL,
            'key' => $key
        ));
    }
    /**
     * Agregar un where a la query
     * @param string $key
     */
    public function addWhereNotNull($key)
    {
        $this->wheres[] = FactoryWhere::create(array(
            'type' => AbstractWhere::TYPE_NOT_NULL,
            'key' => $key
        ));
    }
    /**
     * Agregar un where objeto a la query
     * @param AbstractWhere $where
     */
    public function addWhereFactored($where)
    {
        $this->wheres[] = $where;
    }
    /**
     * 
     *
     * @param array $key
     * @param boolean $withTime
     * @return void
     */
    public function addWhereNextEvents($key, $withTime = false)
    {
        $this->wheres[] = FactoryWhere::create(array(
            'type' => AbstractWhere::TYPE_NEXT_EVENT,
            'key' => $key,
            'with_time' => $withTime,
        ));
    }
    /**
     * 
     *
     * @param array $key
     * @param boolean $withTime
     * @return void
     */
    public function addWherePassEvents($key, $withTime = false)
    {
        $this->wheres[] = FactoryWhere::create(array(
            'type' => AbstractWhere::TYPE_PASS_EVENT,
            'key' => $key,
            'with_time' => $withTime,
        ));
    }
    /**
     * Add whereRaw with keys
     *
     * @param array $keys
     * @param mixed $value
     * @return void
     */
    public function addWhereLikes($keys, $value)
    {
        $this->wheres[] = FactoryWhere::create(array(
            'type' => AbstractWhere::TYPE_LIKES,
            'keys' => $keys,
            'value' => $value
        ));
    }
    /**
     * Add whereRaw with keys
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function addWhereDate($key, $value)
    {
        $this->wheres[] = FactoryWhere::create(array(
            'type' => AbstractWhere::TYPE_DATE,
            'key' => $key,
            'value' => $value
        ));
    }
    /**
     * Add whereRaw with keys
     *
     * @param array $keys
     * @param mixed $value
     * @return void
     */
    public function addWhereIn($key, $value)
    {
        $this->wheres[] = FactoryWhere::create(array(
            'type' => AbstractWhere::TYPE_IN,
            'key' => $key,
            'value' => $value
        ));
    }
    /**
     * Elimina un where del listado
     * @param string $key
     */
    public function removeWhere($key)
    {
        $this->wheres = array_filter($this->wheres, function($w) use ($key){
            if(!$w->isSameKey($key)){
                return true;
            }
        });
    }
    /**
     * Undocumented function
     *
     * @param [type] $key
     * @param [type] $type
     */
    public function removeWhereWithType($key, $type)
    {
        $data = [];
        foreach($this->wheres as $wherObj) {
            if($wherObj->getType() != $type||($wherObj->getType() == $type && !$wherObj->isSameKey($key))){
                $data[] = $wherObj;
            }
        }
        $this->wheres = $data;
    }
    /**
     *
     * @param [type] $type
     */
    public function removeWhereAllType($type)
    {
        $data = [];
        foreach($this->wheres as $wherObj) {
            if($wherObj->getType() != $type){
                $data[] = $wherObj;
            }
        }
        $this->wheres = $data;
    }
    /**
     * Valida si el KEY existe en un where
     *
     * @param string $key
     * @return boolean
     */
    public function hasWhere($key)
    {
        foreach($this->wheres as $where) {
            if($where->isSameKey($key)){
                return true;
            }
        }
        return false;
    }
    /**
     * Devuelve un array con todos los Wheres que coincidan con el KEY
     *
     * @param string $key
     * @return array
     */
    public function getWheresByKey($key)
    {
        $data = [];
        foreach($this->wheres as $wherObj) {
            if($wherObj->isSameKey($key)){
                $data[] = $wherObj;
            }
        }
        return $data;
    }
    /**
     * Devuelve un array con todos los Wheres que coincidan con el TYPE
     *
     * @param string $key
     * @return array
     */
    public function getWheresByType($type)
    {
        $data = [];
        foreach($this->wheres as $wherObj) {
            if(is_a($wherObj, $type)){
                $data[] = $wherObj;
            }
        }
        return $data;
    }

    /**
     * Agregar un order a la query
     * @param string $key
     * @param string $type
     */
    public function addOrder($key, $type)
    {
        $this->orders[] = array('field' => $key, 'type' => $type);
    }

    public function addOrderAsc($key)
    {
        $this->orders[] = array('field' => $key, 'type' => 'asc');
    }

    public function addOrderDesc($key)
    {
        $this->orders[] = array('field' => $key, 'type' => 'desc');
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

        $withs = $this->request->input('withs', []);
        // Verify has ,
        if(is_string($withs) && $withs != ''){
            $this->withs = explode(',', $withs);
        }
        if($this->withs == ''){
            $this->withs = [];
        }

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

        if(isset($jsonObj->orders) && is_array($jsonObj->orders)){
            $this->orders = $jsonObj->orders;
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

        if(isset($jsonObj->orders) && is_array($jsonObj->orders)){
            $this->orders = $jsonObj->orders;
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
    /**
     *
     * @param array $wheres
     * @return void
     */
    public function setWheresByFactory($wheres)
    {
        $this->wheres = FactoryWhere::createAll($wheres);
    }
    /**
     *
     * @return array
     */
    public function getJoins()
    {
        return $this->joins;
    }
    /**
     *
     * @param array $joins
     * @return void
     */
    public function setJoins($joins)
    {
        $this->joins = $joins;
    }
    /**
     *
     * @return array
     */
    public function getWiths()
    {
        return $this->withs;
    }
    /**
     *
     * @return array
     */
    public function getOrders()
    {
        return $this->orders;
    }
    /**
     *
     * @param array $orders
     * @return void
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }
}