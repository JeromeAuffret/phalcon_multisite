<?php


$this->addComponent(
    'api_data',
    [
        'get', 'create', 'update', 'delete'
    ]
);

$this->addComponent(
    'api_form',
    [
        'index', 'get', 'getEmptyRelationTableRow', 'getSelectData', 'getAutocompleteData',
        'create', 'update', 'updateTable', 'updateForm',
        'delete'
    ]
);
