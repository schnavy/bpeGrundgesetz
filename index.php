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
