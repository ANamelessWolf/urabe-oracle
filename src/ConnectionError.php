<?php

/**
 * Class ConnectionError
 * 
 * @package URABE-API
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @version v.1.1 (01/10/2019)
 * @copyright copyright (c) 2018-2020, Nameless Studios
 */
//namespace nameless\urabe;
/**
 * A connection database error
 * Can be caused by a bad connection or bad request
 * @version 1.0.0
 * @api Makoto Urabe DB Manager
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class ConnectionError
{
    const IGNORE_STMT_ORACLE = "statement";
    const IGNORE_STMT_PG = "resource";
    const IGNORE_CONN_PG = "connection";
    /**
     * @var int $code The last error code number.
     */
    public $code;
    /**
     * @var string $message The database connection error text. 
     */
    public $message;
    /**
     * @var string $sql The SQL statement text. If there was no statement, this is an empty string. 
     */
    public $sql;
    /**
     * @var string $file The file where the error was found
     */
    public $file;
    /**
     * @var int The line where the error was found
     */
    public $line;
    /**
     * @var array The error context
     */
    private $err_context;
    /**
     * Gets the error context if the urabe settings allows
     * to print error settings
     *
     * @return mixed The error context
     */
    public function get_err_context()
    {
        //resource field can not be serialized, it has to be removed to avoid problems echoing the response
        $ignoreParams = array(self::IGNORE_STMT_ORACLE, self::IGNORE_STMT_PG, self::IGNORE_CONN_PG);

        foreach ($ignoreParams as &$key)
            if (array_key_exists($key, $this->err_context)) {
            unset($this->err_context[$key]);
        }

        return KanojoX::$settings->show_error_context ? $this->err_context : null;
    }
    /**
     * Sets the error context
     * @param mixed $value The error context
     * @return void
     */
    public function set_err_context($value)
    {
        return $this->err_context = $value;
    }
    /**
     * Formats an exception error
     *
     * @return array The exception error is a mixed array
     */
    public function get_exception_error()
    {
        if (KanojoX::$settings->show_error_details) {
            $err_context = array();
            if (KanojoX::$settings->show_error_context)
                foreach (KanojoX::$errors as &$error) {
                $context = is_null($error->sql) ? array(
                    NODE_MSG => $error->message,
                    NODE_CODE => $error->code,
                    NODE_FILE => $error->file,
                    NODE_LINE => $error->line,
                    NODE_ERROR_CONTEXT => $error->get_err_context()
                ) : array(
                    NODE_CODE => $error->code,
                    NODE_FILE => $error->file,
                    NODE_LINE => $error->line,
                    NODE_ERROR_CONTEXT => $error->get_err_context(),
                    NODE_QUERY => $error->sql
                );
                array_push($err_context, array(NODE_ERROR => $context));
            }

            if (isset($this->sql))
                return array(NODE_QUERY => $this->sql, NODE_CODE => $this->code, NODE_FILE => $this->file, NODE_LINE => $this->line, NODE_ERROR_CONTEXT => $err_context);
            else
                return array(NODE_CODE => $this->code, NODE_FILE => $this->file, NODE_LINE => $this->line, NODE_ERROR_CONTEXT => $err_context);
        } else
            return null;
    }
}
?>