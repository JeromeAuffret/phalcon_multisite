<?php

namespace Base\Modules\Api\Controllers;

use Exception;
use Base\Forms\BaseForm;
use Phalcon\Helper\Str;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

/**
 * Class FormController
 *
 * @package Base\Modules\Api\Controllers
 */
class FormController extends ControllerBase
{
    /**
     * @var BaseForm $form
     */
    protected $form;

    /**
     * @var string $form_namespace
     */
    protected $form_namespace;

    /**
     * @Override
     *
     * Initialize namespaces and objects classes
     *
     * @throws Exception
     */
    protected function instantiateModel()
    {
        $reference = Str::camelize($this->reference);
        $this->form_namespace = $this->dispatcher->dispatchClass($reference.'Form', 'Forms');

        if (!$this->form_namespace) {
            throw new Exception('Form not found for reference : ' . $this->reference, 1);
        }

        if (!empty($this->parameters)) {
            $data = $this->parameters;
            $data[$this->primary_key] = $this->primary_value;
        } else {
            $data = $this->primary_value;
        }

        $this->form = new $this->form_namespace($data);
        $this->model_name = (new $this->form_namespace)->model_name ?: Str::camelize($this->reference);
        $this->model_namespace = $this->dispatcher->dispatchClass($this->model_name, 'Models');

        if ($this->model_namespace)
        {
            if ($this->primary_value) {
                $this->model = $this->model_namespace::findFirst($this->primary_key.' = '.$this->primary_value);

                // In case of relation table form, primary_value is used to explicitly create object with this value to avoid collision
                if (!$this->model && (new $this->form_namespace())->relation_key) {
                    $this->model = new $this->model_namespace();
                }
            } else {
                $this->model = new $this->model_namespace();
            }
        }

        if (!$this->model_namespace || !$this->model) {
            throw new Exception('Not Found', 1);
        }
    }


    /************************************************************
     *
     *                      FORMS METHODS
     *
     ************************************************************/

    /**
     * @return Response|ResponseInterface|void
     */
    public function getAction()
    {
        if($this->request->isGet())
        {
            $query_data = $this->request->getQuery('data') ?: null;

            if ($query_data) {
                $this->form = new $this->form_namespace($query_data);
            }

            $this->response->setContent($this->form->render());
            return $this->response->send();
        }
    }

    /**
     *
     * @return Response|ResponseInterface|void
     * @throws Exception
     */
    public function createAction()
    {
        if ($this->request->isPost())
        {
            $post = $this->request->getPost();

            if($this->request->hasFiles()) {
                $post = $this->form->saveUploadedFiles($post);
            }

            $post = $this->form->sanitizePostData($post);
            $result = $this->form->save($post);

            return $this->validateForm($result);
        }
    }

    /**
     *
     * @return Response|ResponseInterface|void
     * @throws Exception
     */
    public function updateAction()
    {
        if($this->request->isPost())
        {
            $post = $this->request->getPost();

            if($this->request->hasFiles()) {
                $post = $this->form->saveUploadedFiles($post);
            }

            $post = $this->form->sanitizePostData($post);
            $result = $this->form->save($post);

            return $this->validateForm($result);
        }
    }

    /**
     * Send Ajax Call to delete reference
     *
     * @return Response|ResponseInterface|void
     * @throws Exception
     */
    public function deleteAction()
    {
        if($this->request->isDelete())
        {
            $result = $this->form->delete();
            return $this->validateForm($result);
        }
    }

    /**
     * After save, we build errors messages if needed
     *
     * @param BaseForm  $form
     *
     * @return Response|ResponseInterface
     */
    protected function validateForm($form)
    {
        // Return response directly
        if (is_object($form) && $form instanceof Response) {
            return $form;
        }
        else if (is_object($form))
        {
            // Send current form in case of error with validation's messages
            if ($form->getMessages()->count()) {
                // Set status code as Internal Error
                $this->response->setStatusCode(500);
                $this->response->setContent($form->render());
            }
            // Refresh form
            else {
                if ($this->primary_value) {
                    $this->response->setContent((new $form($this->primary_value))->render());
                }
                else {
                    $this->response->setContent($form->render());
                }

            }
        }

        return $this->response;
    }

}
