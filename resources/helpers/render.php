<?php 
    function render($view, $data = []) {
        extract($data); // Extracts variables from the data array.
        ob_start(); // Starts output buffering.
        include __DIR__ . '/../../app/Views/' . $view . '.php';
        $content = ob_get_clean(); // Gets the buffered content and cleans the buffer.
        include __DIR__ . '/../views/layout.php'; // Includes the layout which uses $content.
    }
?>