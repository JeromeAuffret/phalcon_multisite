<?php

namespace Base\Modules\Api\Controllers;

use Exception;
use Phalcon\Filter;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;


class DataController extends ControllerBase
{
    /**
     * @var array
     */
    protected $query_parameters = [];

    /**
     * @throws Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->parseRequestParameters();
    }


    /************************************************************
     *
     *                          ACTIONS
     *
     *  Actions are disabled by default and return 404 status code
     *  This should be overridden in application for specific uses
     *
     ************************************************************/

    /**
     * @return Response|ResponseInterface|void
     */
    public function getAction()
    {
        if ($this->request->isGet())
        {
            $this->response->setStatusCode(404, 'Not Found');
            $this->response->send();
        }
    }

    /**
     * @return Response|ResponseInterface|void
     * @throws Exception
     */
    public function createAction()
    {
        if ($this->request->isPost())
        {
            $this->response->setStatusCode(404, 'Not Found');
            $this->response->send();
        }
    }

    /**
     * @return Response|ResponseInterface|void
     * @throws Exception
     */
    public function updateAction()
    {
        if ($this->request->isPost())
        {
            $this->response->setStatusCode(404, 'Not Found');
            $this->response->send();
        }
    }

    /**
     *  Send Ajax Call to delete reference
     *
     * @return Response|ResponseInterface|void
     * @throws Exception
     */
    public function deleteAction()
    {
        if ($this->request->isDelete())
        {
            $this->response->setStatusCode(404, 'Not Found');
            $this->response->send();
        }
    }


    /************************************************************
     *
     *                      PARSE PARAMETERS
     *
     ************************************************************/

    /**
     *  Parse request to be compatible with phalcon query find() / findFirst()
     */
    private function parseRequestParameters()
    {
        // Request only specific columns like ['columns' = 'column1,column2']
        if ($this->request->has('columns')) {
            $this->query_parameters['columns'] = html_entity_decode($this->request->get('columns', [Filter::FILTER_STRING, Filter::FILTER_TRIM]));
        }

        // Filters query like ['conditions' = filters]
        if ($this->request->has('filters')) {
            $this->query_parameters['conditions'] = html_entity_decode($this->request->get('filters', [Filter::FILTER_STRING, Filter::FILTER_TRIM]));
        }

        // Order query like ['order' = order]
        if ($this->request->has('order')) {
            $this->query_parameters['order'] = $this->request->get('order', [Filter::FILTER_STRING, Filter::FILTER_TRIM]);
        }

        // Order query like ['group' = group]
        if ($this->request->has('group')) {
            $this->query_parameters['group'] = $this->request->get('group', [Filter::FILTER_STRING, Filter::FILTER_TRIM]);
        }

        // Filters query like ['limit' = limit]
        if ($this->request->has('limit')) {
            $this->query_parameters['limit'] = $this->request->get('limit', [Filter::FILTER_ABSINT, Filter::FILTER_TRIM]);
        } else {
            $this->query_parameters['limit'] = 25;
        }

        // Filters query like ['offset' = offset]
        if ($this->request->has('offset')) {
            $this->query_parameters['offset'] = $this->request->get('offset', [Filter::FILTER_ABSINT, Filter::FILTER_TRIM]);
        }
    }

}
