<?php namespace Tots\EloFilter\Where;

use \Illuminate\Database\Eloquent\Model;

/**
 * Description of Configure
 *
 * @author matiascamiletti
 */
class YearWhere extends AbstractWhere 
{
    protected $type = AbstractWhere::TYPE_YEAR;

    public function __construct($data)
    {
        $this->key = $data['key'];
        $this->value = $data['value'];
    }
    /**
     * 
     *
     * @param Model $query
     * @return void
     */
    public function run($query)
    {
        $query->whereRaw('YEAR('.$this->cleanKey($this->key).') = YEAR(?)', [$this->value]);
    }
}