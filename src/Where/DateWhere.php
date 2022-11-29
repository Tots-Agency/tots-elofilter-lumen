<?php namespace Tots\EloFilter\Where;

use \Illuminate\Database\Eloquent\Model;

/**
 * Description of Configure
 *
 * @author matiascamiletti
 */
class DateWhere extends AbstractWhere 
{
    protected $type = AbstractWhere::TYPE_DATE;

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
        $query->whereRaw('DATE('.$this->cleanKey($this->key).') = DATE(?)', $this->value);
    }
}