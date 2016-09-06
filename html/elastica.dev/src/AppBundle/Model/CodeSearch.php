<?php

namespace AppBundle\Model;

use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author rmroz
 */
class CodeSearch {
    
    /**
     *
     * @var string 
     */
    protected $code;
    
    /**
     *
     * @var int 
     */
    protected $page = 1;
    
    /**
     *
     * @var int 
     */
    protected $perPage = 10;
    
    /**
     * 
     * @return int
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * 
     * @param int $page
     * @return \AppBundle\Model\CodeSearch
     */
    public function setPage($page) {
        if($page != null) {
            $this->page = $page;
        }
        return $this;
    }

    
    /**
     * 
     * @return int
     */
    public function getPerPage() {
        return $this->perPage;
    }

    /**
     * 
     * @param int $perPage
     * @return \AppBundle\Model\CodeSearch
     */
    public function setPerPage($perPage) {
        if ($perPage != null) {
            $this->perPage = $perPage;
        }
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCode() {
        return $this->code;
    }
    
    /**
     * 
     * @param string $code
     */
    public function setCode($code) {
        $this->code = $code;
    }
    
    /**
     * 
     * @param Request $request
     */
    public function handleRequest(Request $request) 
    {
        $this->setPage($request->get('page', 1));
        $this->setPerPage($request->get('perPage', 10));
    }
    
}
