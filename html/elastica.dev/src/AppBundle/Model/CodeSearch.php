<?php

namespace AppBundle\Model;

use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author rmroz
 */
class CodeSearch {
    
    
    protected $code;
    
    protected $page = 1;
    
    protected $perPage = 10;

    protected $sort = 'code';
    
    protected $direction = 'desc';
    
    public function getSort() {
        return $this->sort;
    }

    public function getDirection() {
        return $this->direction;
    }

    public function setSort($sort) {
        $this->sort = $sort;
    }

    public function setDirection($direction) {
        $this->direction = $direction;
    }
  
    public function getPage() {
        return $this->page;
    }

    public function setPage($page) {
        if($page != null) {
            $this->page = $page;
        }
        return $this;
    }

    public function getPerPage() {
        return $this->perPage;
    }

    public function setPerPage($perPage) {
        if ($perPage != null) {
            $this->perPage = $perPage;
        }
        return $this;
    }

    public function getCode() {
        return $this->code;
    }
    
    public function setCode($code) {
        $this->code = $code;
    }
    
    public function handleRequest(Request $request) 
    {
        $this->setPage($request->get('page', 1));
        // $this->setPerPage($request->get('perPage', 10));
    }
    
}
