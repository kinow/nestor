<?php namespace Nestor\Model;

use Eloquent, Validator;
use Nestor\Util\ValidationException;

/**
 * @see http://blog.igeek.info/2014/supercharged-models-in-laravel/
 */
class BaseModel extends Eloquent {

    protected static $_rules = array(
        'create' => array(),
        'update' => array(),
    );
 
    protected static $_customErrors = array();
 
    protected static function _validate( $data, $rules ) 
    {
        $validation = Validator::make($data, $rules, static::$_customErrors);
 
        if ($validation->fails())
        {
            //data validation failed, throw an exception
            //ValidationException is a custom exception which
            //can accept MessageBag instance as first argument
            throw new ValidationException($validation->messages());
        }
 
        //all good & hunky dory
        return true;
    }
 
    public static function create(array $attributes) 
    {
        try 
        {
            static::_validate($attributes, static::$_rules['create']);
        }
        catch (ValidationException $e) 
        {
            //we would want to catch it in controller
            //its of no use here, so lets re-throw
            //the exception
            throw $e;
        }
 
        //all good
        return parent::create($attributes);
    }

    public function update(array $attributes = array())
    {
        try 
        {
            static::_validate($attributes, static::$_rules['update']);
        } 
        catch (ValidationException $e) 
        {
            //we would want to catch it in controller
            //its of no use here, so lets re-throw
            //the exception
            throw $e;
        }
 
        //all good
        return parent::update($attributes);
    }
}