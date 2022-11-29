<?php

namespace Tots\EloFilter;

use Illuminate\Http\Request;

class QueryRequest
{
    /**
     *
     * @var Request
     */
    protected $request;
    /**
     *
     * @var integer
     */
    protected $page = 1;
    /**
     *
     * @var integer
     */
    protected $perPage = 50;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function processRequest()
    {
        // Get normal params
        $this->page = $this->request->input('page', 1);
        $this->perPage = $this->request->input('per_page', 50);
        
    }
}