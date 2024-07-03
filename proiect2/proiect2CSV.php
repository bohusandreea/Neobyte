<?php

$start_time = microtime(true);
$start_time_array = explode(' ', $start_time);
$start_time_sec = $start_time_array[1];
$start_time_microsec = $start_time_array[0];


$search = $_GET['search'];
$limit = $_GET['limit'];
$offset = $_GET['offset'];

if ($search != '' && !preg_match('/^[\p{L}0-9\s\-\.\/\+\(\)]+$/iu', $search)) {
  echo "Error: Invalid search input. Please use only letters, numbers, spaces, hyphens, periods, forward slashes, plus signs, and parentheses.";
  exit;
}
if ($limit > 8808) {
  echo "Error: Invalid limit input!";
  exit;
}

$handle = fopen('netflix_titles.csv', 'r');
if ($handle === false) {
  echo "Error: Unable to open file";
  exit;
}

fgets($handle);


$rowCount = 0;

for ($i = 0; $i < $offset; $i++) {
  fgets($handle);
}

$data = array();
while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
  $title = $row[2];
  if (stripos($title, $search) !== FALSE) {
    $data[] = $row;
    $rowCount++;
  }
  if ($rowCount >= $limit) {
    break;
  }
}

fclose($handle);

$end_time = microtime(true);
$end_time_array = explode(' ', $end_time);
$end_time_sec = $end_time_array[1];
$end_time_microsec = $end_time_array[0];

$executionTime = ($end_time_sec - $start_time_sec) + ($end_time_microsec - $start_time_microsec);
$numRecords = count($data);


?>
<html>

<body>
  <h2>Search Results</h2>
  <table>
    <thead>
      <tr>
        <th>show_id</th>
        <th>type</th>
        <th>title</th>
        <th>director</th>
        <th>cast</th>
        <th>country</th>
        <th>date_added</th>
        <th>release year</th>
        <th>rating</th>
        <th>duration</th>
        <th>listed in</th>
        <th>description</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $row): ?>
        <tr>
          <?php foreach ($row as $cell): ?>
            <td><?= $cell ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="execution-time">Execution time: <?= number_format($executionTime * 100, 4) ?> seconds</div>
  <div class="num-records">Number of records: <?= $numRecords ?></div>
</body>

</html>