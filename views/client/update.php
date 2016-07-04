<?php

$this->title = 'Spy | Actualizar: ' . $client->name;
echo $this->render('_form',[
    'update' => true,
    'client' => $client,
    'contact' => $contact,
]);
?>