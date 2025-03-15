<?php
// Fetch the raw encoded text from encoded.txt
$encodedText = file_get_contents('encoded-humanrights.txt');

// Fetch and process the vocabulary file
$vocabContent = file_get_contents('vocabulary.txt');
$lines = explode("\n", $vocabContent);
$vocabHTML = '';

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line)) continue;
    // This regex handles lines in both formats:
    // Format 1: [token] id
    // Format 2: [x][y] -> [mappedToken] id
    if (preg_match('/\[(.*?)\](?:\s*->\s*\[(.*?)\])?\s*(\d+)/', $line, $matches)) {
        // If a mapping exists, use the mapped token; otherwise, use the first token.
        $token = isset($matches[2]) && $matches[2] !== '' ? $matches[2] : $matches[1];
        $id = $matches[3];
        $vocabHTML .= '<div class="entry"><span class="token">[' . htmlspecialchars($token) . ']</span><span class="id">' . htmlspecialchars($id) . '</span></div>' . "\n";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BPE Human Rights</title>
  <link rel="stylesheet" href="style.css" />
    <!-- Primary Meta Tags -->
    <title>BPE Human Rights</title>
    <meta name="title" content="BPE Human Rights" />
    <meta name="description" content="Universal Declaration of Human Rights tokenized for LLMs" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://humanrights.davidwahrenburg.de/" />
    <meta property="og:title" content="BPE Human Rights" />
    <meta property="og:description" content="Universal Declaration of Human Rights tokenized for LLMs" />
    <meta property="og:image" content="thumb.jpg" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://humanrights.davidwahrenburg.de/" />
    <meta property="twitter:title" content="BPE Human Rights" />
    <meta property="twitter:description" content="Universal Declaration of Human Rights tokenized for LLMs" />
    <meta property="twitter:image" content="thumb.jpg" />
</head>
<body>
  <div class="header">
      <h1>Universal Declaration of Human Rights tokenized for LLMs</h1>
  </div>
  <div class="wrapper">
    <section class="text">
      <h3>BPE Encoded Output:</h3>
      <p><?php echo htmlspecialchars($encodedText); ?></p>
    </section>
    <section class="vocab">
      <h3>Vocabulary:</h3>
      <div class="columns">
      <?php echo $vocabHTML; ?>
    </div>
    </section>
  </div>
</body>
</html>
