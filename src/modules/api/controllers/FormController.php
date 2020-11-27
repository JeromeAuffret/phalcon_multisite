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



}
