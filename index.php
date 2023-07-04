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
    <a href="/"><img style="display:block; margin-left:auto; margin-right:auto;" src="/PlexLogo.png" /></a>
    <form>
        <input type="text" id="search" name="search" style="width:100%;" placeholder="Search for something...">
        <br />
        <br />
        <button type="submit" class="sbutton">Search</button>
        &nbsp;&nbsp;<a href="https://ourworldoftext.com"><button type="button" class="sbutton">I want to go to a better site</button></a>
    </form>
    <script>
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();
    
    // Define the file path
    var filePath = "/found.txt";
    
    // Make a GET request to retrieve the file content
    xhr.open("GET", filePath, true);
    
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Split the file content into an array of lines
        var lines = xhr.responseText.split("\n");
    
        // Get the number of lines in the file
        var lineCount = lines.length - 1; // Subtract 1 from the line count
    
        // Display the line count in the scontainer div
        var scontainerDiv = document.querySelector(".scontainer");
        scontainerDiv.innerHTML += "<b>" + lineCount + "</b> pages to find!";
      }
    };
    
    // Send the request
    xhr.send();
    
    </script>
</div>
';
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
