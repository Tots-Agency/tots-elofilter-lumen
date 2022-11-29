<?php namespace Tots\EloFilter\Where;

use \Illuminate\Database\Capsule\Manager as DB;
use \Illuminate\Database\Eloquent\Model;

/**
 * Description of Configure
 *
 * @author matiascamiletti
 */
class BetweenWhere extends AbstractWhere 
{
    protected $type = AbstractWhere::TYPE_BETWEEN;
    
    protected $from = '';
    protected $to = '';

    public function __construct($data)
    {
        $this->key = $data['key'];
        $this->from = $data['from'];
        $this->to = $data['to'];
    }
    /**
     * 
     *
     * @param Model $query
     * @return void
     */
    public function run($query)
    {
        $query->whereBetween($this->key, [$this->from, $this->to]);
    }
    /**
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }
    /**
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }
}