<?php

namespace App\Filters;

use Symfony\Component\HttpFoundation\Request;

abstract class Filters
{

    protected $request, $builder;
    protected $filters = [];



    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;


        

        foreach ($this->getFilters() as $filter => $value) {
            
            if (!method_exists($this,$filter)) return;


            $this->$filter($value);
        }


        return $this->builder;
    }

    public function getFilters()
    {
        return $this->request->only($this->filters);
    }
}
