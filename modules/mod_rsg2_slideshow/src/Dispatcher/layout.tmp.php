<?php

// assign local layout

protected
function getLayoutData(): array
{
    $data = parent::getLayoutData();

    $params = $data['params'];

    if ($params->get('param_name')) {
        $params->set('layout', 'first_layout');
    } else {
        $params->set('layout', 'second_layout');
    }

    $data['params'] = $params;

    return $data;
}



