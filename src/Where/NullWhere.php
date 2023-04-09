<?php namespace Tots\EloFilter\Where;

use \Illuminate\Database\Eloquent\Model;

/**
 * Description of Configure
 *
 * @author matiascamiletti
 */
class NullWhere extends AbstractWhere 
{
    protected $type = AbstractWhere::TYPE_NULL;

    public function __construct($data)
    {
        $this->key = $data['key'];
    }
    /**
     * 
     *
     * @param Model $query
     * @return void
     */
    public function run($query)
    {
        $query->whereNull($this->cleanKey($this->key));
    }
}