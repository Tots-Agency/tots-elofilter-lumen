<?php namespace Tots\EloFilter\Where;

/**
 * Description of Configure
 *
 * @author matiascamiletti
 */
class FactoryWhere
{
    /**
     * 
     */
    static $types = [
        AbstractWhere::TYPE_LIKES => LikesWhere::class,
        AbstractWhere::TYPE_DATE => DateWhere::class,
        AbstractWhere::TYPE_WEEK => WeekWhere::class,
        AbstractWhere::TYPE_MONTH => MonthWhere::class,
        AbstractWhere::TYPE_YEAR => YearWhere::class,
        AbstractWhere::TYPE_BETWEEN => BetweenWhere::class,
        AbstractWhere::TYPE_NEXT_EVENT => NextEventWhere::class,
        AbstractWhere::TYPE_PASS_EVENT => PassEventWhere::class,
        AbstractWhere::TYPE_EQUAL => EqualWhere::class,
        AbstractWhere::TYPE_IN => InWhere::class,
        AbstractWhere::TYPE_GREATER_THAN => GreaterThanWhere::class,
        AbstractWhere::TYPE_LESS_THAN => LessThanWhere::class,
        AbstractWhere::TYPE_NULL => NullWhere::class,
        AbstractWhere::TYPE_NOT_NULL => NotNullWhere::class,
    ];
    /**
     * Create where
     *
     * @param array $data
     * @return void
     */
    public static function create($data)
    {
        $dataArray = (array)$data;
        $className = self::$types[$dataArray['type']];
        return new $className($dataArray);
    }
    /**
     * Create all wheres
     *
     * @param array $wheres
     * @return void
     */
    public static function createAll($wheres)
    {
        $items = [];
        foreach($wheres as $where){
            $items[] = self::create($where);
        }
        return $items;
    }
}