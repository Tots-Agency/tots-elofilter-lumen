<?php namespace Tots\EloFilter\Where;

use \Illuminate\Database\Eloquent\Model;

/**
 * Description of Configure
 *
 * @author matiascamiletti
 */
class LikesWhere extends AbstractWhere 
{
    protected $type = AbstractWhere::TYPE_LIKES;
    /**
     * List of keys
     *
     * @var array
     */
    protected $keys = [];

    public function __construct($data)
    {
        $this->keys = $data['keys'];
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
        $raw = '';
        $values = [];
        // for each all keys
        $isFirst = true;
        foreach($this->keys as $key){
            if(!$isFirst){
                $raw .= ' OR ';
            }

            // Verify if key is concat
            if(stripos($key, '+') !== false){
                $subkeys = str_replace('+', ', " ",', $key);
                $raw .= 'CONCAT('.$subkeys.')' . ' REGEXP ?';
            } else {
                $raw .= $key . ' REGEXP ?';
            }

            $values[] = $this->value;
            $isFirst = false;
        }
        //$values = $search . '|' . implode('|', explode(' ', $search));
        $query->whereRaw('('.$raw.')', $values);
    }
    /**
     * Undocumented function
     *
     * @param array $keys
     * @return void
     */
    public function setKeys($keys)
    {
        $this->keys = $keys;
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getKeys()
    {
        return $this->keys;
    }
    /**
     * Verify if key is same
     *
     * @param string $key
     * @return boolean
     */
    public function isSameKey($key)
    {
        foreach($this->keys as $keyInt){
            if($keyInt == $key){
                return true;
            }
        }

        return false;
    }
}