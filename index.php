<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список сделок</title>
</head>
<body>
<form method="GET" action="search.php" class="search-form">
  <label for="search_query" class="search-label">Поиск сделок:</label>
  <input type="text" id="search_query" name="q" value="<?php echo $searchQuery; ?>" class="search-input" placeholder="Введите название сделки">
  <button type="submit" class="search-button">Найти</button>
</form>
<?php
require_once (__DIR__.'/crest.php');

// Получение списка всех сделок
$result = CRest::call(
  'crm.deal.list',
  array(
    'order' => array("STAGE_ID" => "ASC"),
    'filter' => array(),
    'select' => array("ID", "TITLE", "STAGE_ID", "PROBABILITY", "OPPORTUNITY", "CURRENCY_ID")
  )
);

// Проверка наличия и успешности получения списка сделок
if(isset($result['result']) && !empty($result['result'])) {
  echo '<h2>Список сделок</h2>';
  echo '<table border="1">';
  echo '<tr><th>ID</th><th>Название</th><th>Стадия</th><th>Вероятность</th><th>Сумма</th><th>Действие</th></tr>';
  foreach ($result['result'] as $deal) {
    echo '<tr>';
    echo '<td>' . $deal['ID'] . '</td>';
    echo '<td>' . $deal['TITLE'] . '</td>';
    echo '<td>' . $deal['STAGE_ID'] . '</td>';
    echo '<td>' . $deal['PROBABILITY'] . '%</td>';
    echo '<td>' . $deal['OPPORTUNITY'] . ' ' . $deal['CURRENCY_ID'] . '</td>';
    echo '<td><a href="deal.php?id=' . $deal['ID'] . '">Просмотреть сделку</a></td>';
    echo '</tr>';
  }
  echo '</table>';
} else {
  echo 'Нет данных о сделках.';
}
?>

<style type="text/css">
table {
  border-collapse: collapse;
  width: 100%;
  margin: 20px auto;
  border: 1px solid #ddd;
}

th, td {
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
  font-weight: bold;
}

td {
  border-top: 1px solid #ddd;
}

tr:hover {
  background-color: #f9fafb;
}

a {
  text-decoration: none;
  color: #337ab7;
}

a:hover {
  color: #286090;
}
.search-form {
  display: flex;
  align-items: center;
  /* Remove margin: 20px auto; */
  justify-content: flex-end; /* Align to right side */
}

.search-label {
  margin-right: 10px;
  font-weight: bold;
}

.search-input {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
}

.search-button {
  background-color: #007bff;
  color: #fff;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.search-button:hover {
  background-color: #0062cc;
}
</style>
</body>
</html>
