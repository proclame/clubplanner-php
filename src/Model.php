<?php

namespace Proclame\Clubplanner;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Proclame\Clubplanner\Exceptions\ClubplannerApiException;

abstract class Model
{
    protected $fillable = [];
    protected $connection;
    protected $attributes = [];
    protected $attribute_changes = [];

    protected $isLoaded = false;


    /**
     * @var array
     */
    protected $singleNestedEntities = [];

    /**
     * Array containing the name of the attribute that contains nested objects as key and an array with the entity name
     * and json representation type.
     *
     * JSON representation of an array of objects (NESTING_TYPE_ARRAY_OF_OBJECTS) : [ {}, {} ]
     * JSON representation of nested objects (NESTING_TYPE_NESTED_OBJECTS): { "0": {}, "1": {} }
     *
     * @var array
     */
    protected $multipleNestedEntities = [];

    /**
     * Model constructor.
     * @param \Proclame\Clubplanner\Connection $connection
     * @param array $attributes
     */
    public function __construct(Connection $connection, array $attributes = [])
    {
        $this->connection = $connection;
        $this->fill($attributes);
    }
    /**
     * Fill the entity from an array.
     *
     * @param array $attributes
     * @param bool $first_initialize
     */
    protected function fill(array $attributes)
    {
        foreach ($this->fillableFromArray($attributes) as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
    }



    /**
     * Get the fillable attributes of an array.
     *
     * @param array $attributes
     *
     * @return array
     */
    protected function fillableFromArray(array $attributes)
    {
        if (count($this->fillable) > 0) {
            return array_intersect_key($attributes, array_flip($this->fillable));
        }

        return $attributes;
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function isFillable($key)
    {
        if (count($this->fillable) > 0) {
            return in_array($key, $this->fillable, true);
        }
        return true;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function setAttribute($key, $value)
    {
        if (! isset($this->attribute_changes[$key])) {
            $from = null;

            if (isset($this->attributes[$key])) {
                $from = $this->attributes[$key];
            }

            $this->attribute_changes[$key] = [
                'from' => $from,
                'to' => $value,
            ];
        } else {
            $this->attribute_changes[$key]['to'] = $value;
        }

        $this->attributes[$key] = $value;
    }


    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }

        return null;
    }


    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        if ($this->isFillable($key)) {
            $this->setAttribute($key, $value);
        }
    }


    /**
     * Create a new object with the response from the API.
     *
     * @param array $response
     *
     * @return static
     */
    public function makeFromResponse(array $response)
    {
        $entity = new static($this->connection);
        $entity->selfFromResponse($response);

        return $entity;
    }

    /**
     * Recreate this object with the response from the API.
     *
     * @param array $response
     *
     * @return $this
     */
    public function selfFromResponse(array $response)
    {
        $this->fill($response, true);

        foreach ($this->getSingleNestedEntities() as $key => $value) {
            if (isset($response[$key])) {
                $entityName = $value;
                $this->$key = new $entityName($this->connection, $response[$key]);
            }
        }

        foreach ($this->getMultipleNestedEntities() as $key => $value) {
            if (isset($response[$key])) {
                $entityName = $value['entity'];
                /** @var self $instantiatedEntity */
                $instantiatedEntity = new $entityName($this->connection);
                $this->$key = $instantiatedEntity->collectionFromResult($response[$key]);
            }
        }

        $this->isLoaded = true;
        return $this;
    }

    /**
     * @param array $result
     *
     * @return array
     */
    public function collectionFromResult(array $result)
    {
        // If we have one result which is not an assoc array, make it the first element of an array for the
        // collectionFromResult function so we always return a collection from filter
        if ((bool) count(array_filter(array_keys($result), 'is_string'))) {
            $result = [$result];
        }

        $collection = [];
        foreach ($result as $r) {
            $collection[] = $this->makeFromResponse($r);
        }

        return $collection;
    }


    /**
     * @return mixed
     */
    public function getSingleNestedEntities()
    {
        return $this->singleNestedEntities;
    }

    /**
     * @return array
     */
    public function getMultipleNestedEntities()
    {
        return $this->multipleNestedEntities;
    }

    public function load()
    {
        // @tTODO: Load from API
        if ($this->isLoaded !== true) {
            if (isset($this->attributes['id'])) {
                // $this = $this->find($this->id);
            } else {
                // $this = $this->getFirst($this->attributes);
            }
        }
        $this->isLoaded = true;
        return $this;
    }






    /**
     * @param array $params
     *
     * @return mixed
     *
     * @throws \Proclame\Clubplanner\Exceptions\ApiException
     */
    public function get($params = [])
    {
        $result = $this->connection()->get($this->getEndpoint(true), $params);

        return $this->collectionFromResult($result);
    }


    public function getFirst($params = [])
    {
        $result = $this->connection()->get($this->getEndpoint(), $params);

        return $this->collectionFromResult($result)[0];
    }

    /**
     * Get the connection instance.
     *
     * @return \Proclame\Clubplanner\Connection
     */
    public function connection()
    {
        return $this->connection;
    }

    /**
     * @param string|int $id
     *
     * @return mixed
     *
     * @throws \Proclame\Clubplanner\Exceptions\ClubplannerApiException
     */
    public function find($searchValue, $key = 'id')
    {
        try {
            $result = $this->connection()->get($this->getEndpoint(), [$key => $searchValue]);
        } catch (ClubplannerApiException $e) {
            return null;
        }

        return $this->makeFromResponse($result);
    }


    /**
     * @return string
     */
    public function getEndpoint($multiple = false)
    {
        if ($multiple) {
            return $this->endpointMultiple ?? $this->endpoint . 's';
        }

        return $this->endpoint;
    }

    public function __debugInfo()
    {
        return $this->attributes;
    }
}
