<?php
require_once (__DIR__.'/crest.php');

// Получение ID сделки из параметра
$dealId = $_GET['id'];

// Получение информации о сделке
$result = CRest::call(
  'crm.deal.get',
  array(
    'id' => $dealId,
    'select' => array("ID", "TITLE", "STAGE_ID", "PROBABILITY", "OPPORTUNITY", "CURRENCY_ID", "CONTACT_ID", "COMPANY_ID", "ASSIGNED_BY_ID", "DESCRIPTION")
  )
);

// Проверка наличия и успешности получения информации о сделке
if(isset($result['result'])) {
    $deal = $result['result'];
    echo '<h2>Информация о сделке</h2>';
    echo '<table class="deal-info">';
    echo '<tr><th>ID</th><td>' . $deal['ID'] . '</td></tr>';
    echo '<tr><th>Название</th><td>' . $deal['TITLE'] . '</td></tr>';
    echo '<tr><th>Стадия</th><td>' . $deal['STAGE_ID'] . '</td></tr>';
    echo '<tr><th>Вероятность</th><td>' . $deal['PROBABILITY'] . '%</td></tr>';
    echo '<tr><th>Сумма</th><td>' . $deal['OPPORTUNITY'] . ' ' . $deal['CURRENCY_ID'] . '</td></tr>';
    echo '<tr><th>Контакт</th><td><a href="related-deals-with-contacts.php?id=' . $deal['CONTACT_ID'] . '" class="contact-link">';

    // Запрос к API для получения информации о контакте
    $contactResult = CRest::call(
        'crm.contact.get',
        array(
            'id' => $deal['CONTACT_ID'],
            'select' => array("NAME", "LAST_NAME")
        )
    );

    // Проверка наличия и успешности получения информации о контакте
    if(isset($contactResult['result'])) {
        $contact = $contactResult['result'];
        echo $contact['NAME'] . ' ' . $contact['LAST_NAME'];
    } else {
        echo $deal['CONTACT_ID'];
    }

    echo '</a></td></tr>';

    // Запрос к API для получения информации о компании
    $companyResult = CRest::call(
        'crm.company.get',
        array(
            'id' => $deal['COMPANY_ID'],
            'select' => array("TITLE")
        )
    );

    // Проверка наличия и успешности получения информации о компании
    if(isset($companyResult['result'])) {
        $company = $companyResult['result'];
        echo '<tr><th>Компания</th><td><a href="related-deals-with-company.php?id=' . $deal['COMPANY_ID'] . '" class="company-link">' . $company['TITLE'] . '</a></td></tr>';
    } else {
        echo '<tr><th>Компания</th><td>Не найдена</td></tr>';
    }

    echo '<tr><th>Ответственный</th><td>' . $deal['ASSIGNED_BY_ID'] . '</td></tr>';
    echo '<tr><th>Описание</th><td>' . $deal['DESCRIPTION'] . '</td></tr>';
    echo '</table>';
} else {
    echo 'Сделка не найдена.';
}
?>

<style type="text/css">
.contact-link{
  display: inline-flex;
  align-items: center;
  text-decoration: none;
  color: #007bff;
}
.company-link {
  display: inline-flex;
  align-items: center;
  text-decoration: none;
  color: #007bff;

}

.company-link:hover {
  background-color: #f2f2f2;
}
.deal-info, h2 {
  border-collapse: collapse;
  width: 50%;
  margin: 20px auto;
}

.deal-info th, .deal-info td {
  border: 1px solid #ddd;
  padding: 8px;
}

.deal-info th {
  text-align: left;
  background-color: #f2f2f2;
}

.deal-info td {
  text-align: left;
}
.deal-info th {
  font-weight: bold;
}

.deal-info td.currency {
  text-align: left;
  color: green;
}

.deal-info td.description {
  white-space: pre-wrap;
}
</style>