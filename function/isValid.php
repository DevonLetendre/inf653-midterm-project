<?php

function isValid($id, $model) {
  // Set the model's ID to the provided ID
    $model->id = $id;
    // Call the model's read_single() method to check if the record exists
    $modelResult = $model->read_single();
    // Return the result (false if not found, otherwise the fetched record)
    return $modelResult;
  }
?>