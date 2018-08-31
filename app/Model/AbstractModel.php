<?php

namespace App\Model;

abstract class AbstractModel
{
    /** @var string */
    protected $id;

    /** @var array */
    protected $map;

    public $supportedRelations = [];

    protected $abstractMap = ['id' => 'id'];

    public function __construct($data)
    {
        $this->hydrate($data);
    }

    public function hydrate($data): void
    {
        $data = (array) $data;
        foreach ($this->map as $field => $method) {

            if (is_numeric($field)) {
                // $field & $method are same
                $field = $method;
            }

            $value = array_key_exists($field, $data) ? $data[$field] : null;
            $method = str_replace(' ', '',
                ucwords(str_replace('_', ' ', $method)));
            $method = 'set' . $method;

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function extract(): array
    {
        $data = [];
        foreach ($this->map as $field => $method) {

            if (is_numeric($field)) {
                // $field & $method are same
                $field = $method;
            }

            $method = str_replace(' ', '',
                ucwords(str_replace('_', ' ', $method)));
            $method = 'get' . $method;

            if (method_exists($this, $method)) {
                $value = $this->$method();
                if ($value instanceof AbstractModel) {
                    $value = $value->extract();
                }
                if (is_array($value)) {
                    $items = [];
                    foreach($value as $item) {
                        if ($item instanceof AbstractModel) {
                            $items[] = $item->extract();
                        }
                    }
                }
                $data[$field] = $value;
            }
        }
        if (!empty($this->supportedRelations)) {
            $links = [];
            foreach ($this->supportedRelations as $relation) {
                //TODO get endpoint from model
                $links[] = '/tweet/'.$this->getId().'/'.$relation;
            }
        }


        return $data;
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return array_merge($this->abstractMap, $this->map);
    }
}
