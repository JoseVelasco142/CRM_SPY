<?php

$this->title = 'Spy | Nuevo cliente';
echo $this->render('_form',[
    'update' => false,
    'client' => $client,
    'contact' => $contact,
]);
?>

