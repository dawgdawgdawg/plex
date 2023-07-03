<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/plex.css">
</head>
<body>
<?php
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $foundContent = searchContent($searchTerm);

    if (!empty($foundContent)) {
        echo '<h2>Search Results:</h2>';
        echo '<ul>';
        foreach ($foundContent as $content) {
            echo '<li><a href="' . $content['url'] . '">' . $content['title'] . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No results found for "' . $searchTerm . '".</p>';
    }
} else {
    echo '
    <div class="scontainer">
        <a href="/"><img src="/PlexLogo.png" /></a>
        <form>
            <input type="text" id="search" name="search" style="width:100%;" placeholder="Search for something...">
            <br />
            <br />
            <button type="submit" class="sbutton">Search</button>
        </form>
    </div>';
}

function searchContent($searchTerm) {
    $foundContent = array();
    $file = fopen('found.txt', 'r');

    while (!feof($file)) {
        $line = fgets($file);
        $lineParts = explode('|||', $line);

        if (count($lineParts) === 2) {
            $url = trim($lineParts[0]);
            $title = trim($lineParts[1]);

            if (stripos($title, $searchTerm) !== false) {
                $foundContent[] = array('url' => $url, 'title' => $title);
            }
        }
    }

    fclose($file);

    return $foundContent;
}
?>
</body>
</html>
