<?php namespace Tots\EloFilter\Where;

use \Illuminate\Database\Eloquent\Model;

/**
 * Description of Configure
 *
 * @author matiascamiletti
 */
class PassEventWhere extends AbstractWhere 
{
    protected $type = AbstractWhere::TYPE_PASS_EVENT;
    /**
     *
     * @var boolean
     */
    protected $withTime = false;

    public function __construct($data)
    {
        $this->key = $data['key'];
        $this->withTime = $data['with_time'];
    }
    /**
     * 
     *
     * @param Model $query
     * @return void
     */
    public function run($query)
    {
        if($this->withTime){
            $query->whereRaw('DATETIME('.$this->cleanKey($this->key).') < DATETIME(NOW())');
        } else {
            $query->whereRaw('DATE('.$this->cleanKey($this->key).') < DATE(NOW())');
        }
    }
}