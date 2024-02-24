<?php

require_once (__DIR__.'/crest.php');

// Получение поискового запроса из GET-параметра
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Формирование фильтра по названию
$filter = array('TITLE' => $searchQuery);

// Получение списка сделок с учетом фильтра
$result = CRest::call(
  'crm.deal.list',
  array(
    'order' => array("STAGE_ID" => "ASC"),
    'filter' => $filter,
    'select' => array("ID", "TITLE", "STAGE_ID", "PROBABILITY", "OPPORTUNITY", "CURRENCY_ID")
  )
);

// Проверка наличия и успешности получения списка сделок
if (isset($result['result']) && !empty($result['result'])) {
  // Вывод результатов поиска
  echo '<h2>Результаты поиска:</h2>';
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
  // Отображение сообщения о том, что сделок не найдено
  echo 'Сделок, соответствующих запросу "' . $searchQuery . '", не найдено.';
}

?>
<style type="text/css">

/* Сброс стилей */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Контейнер */
.container {
  max-width: 960px;
  margin: 0 auto;
  padding: 20px;
}

/* Заголовок */
h1 {
  font-size: 24px;
  margin-bottom: 10px;
}

/* Форма поиска */
form {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

label {
  margin-right: 10px;
}

#search_query {
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
  width: 300px;
}

button {
  padding: 5px 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f0f0f0;
  cursor: pointer;
}

/* Таблица */
table {
  width: 100%;
  border-collapse: collapse;
  border: 1px solid #ccc;
}

th, td {
  padding: 5px;
  border: 1px solid #ccc;
}

th {
  text-align: center;
}

/* Чередование цветов */
tr:nth-child(even) {
  background-color: #f9f9f9;
}
</style>
