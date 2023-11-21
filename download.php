<?php

function downloadGitHubRepository($repositoryUrl, $destination)
{
    $zipUrl = $repositoryUrl . '/archive/master.zip';

    $zipFile = file_get_contents($zipUrl);

    if ($zipFile) {
        file_put_contents($destination, $zipFile);
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $repositoryUrl = $_POST['repository_url'];
    $destination = 'downloaded_repository.zip';

    if (filter_var($repositoryUrl, FILTER_VALIDATE_URL) !== false) {
        if (downloadGitHubRepository($repositoryUrl, $destination)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($destination) . '"');
            header('Content-Length: ' . filesize($destination));
            readfile($destination);
            exit;
        } else {
            echo 'Failed to download the repository.';
        }
    } else {
        echo 'Invalid repository URL.';
    }
}

?>
