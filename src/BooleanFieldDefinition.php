<?php
/**
 * Class BooleanFieldDefinition | FieldDefinition.php
 *
 * @package URABE-API
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @version v.1.1 (01/10/2019)
 * @copyright copyright (c) 2018-2020, Nameless Studios
 */
include_once "FieldDefinition.php";
/**
 * String Field Definition Class
 * 
 * This class encapsulates a table column definition and format it values to JSON field value
 * Each table field is associated to a column and stores its index and data type.
 * 
 * @api Makoto Urabe DB Manager
 */
class BooleanFieldDefinition extends FieldDefinition
{
    /**
     * Initialize a new instance of a Field Definition class
     *
     * @param string $index The column index
     * @param string $column The column name
     * @param string $data_type The data type name
     */
    public function __construct($index, $column, $data_type)
    {
        parent::__construct($index, $column, $data_type);
    }
    /**
     * Gets the value from a string in the row definition data type
     *
     * @param string $value The selected value as string
     * @return boolean The value formatted as a boolean
     */
    public function get_value($value)
    {
        if (is_null($value))
            return null;
        else if (strval(strtolower($value) == 'true') || strval(strtolower($value) == 'false'))
            return strval(strtolower($value)) == 'true';
        else
            return intval($value) == 1;
    }
    /**
     * Formats a value to be use as a place holder parameter
     *
     * @param DBDriver $driver The selected value as string
     * @param mixed $value The selected value as string
     * @return mixed The value as the same type of the table definition.
     */
    public function format_value($driver, $value)
    {
        if ($this->data_type == PARSE_AS_BOOLEAN)
            return $driver == DBDriver::PG ? ($value == true ? "t" : "f") : ($value == true ? 1 : 0);
        else
            return parent::format_value($driver, $value);
    }
}
?>