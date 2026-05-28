<?php
// edit.php redirects to create.php with ID parameter (shared form)
$id = (int)($_GET['id'] ?? 0);
header('Location: create.php?id=' . $id);
exit;
