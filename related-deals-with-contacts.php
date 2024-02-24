<?php
require_once (__DIR__.'/crest.php');

// Получение ID контакта из параметра
$contactId = $_GET['id'];

// Получение информации о контакте
$result = CRest::call(
    'crm.contact.get',
    array(
        'id' => $contactId,
        'select' => array("ID", "NAME", "LAST_NAME")
    )
);

// Проверка наличия и успешности получения информации о контакте
if(isset($result['result'])) {
    $contact = $result['result'];
    echo '<h2>Сделки, связанные с контактом "' . $contact['NAME'] . ' ' . $contact['LAST_NAME'] . '":</h2>';

    // Запрос к API для получения списка сделок, связанных с этим контактом
    $dealResult = CRest::call(
        'crm.deal.list',
        array(
            'order' => array("STAGE_ID" => "ASC"),
            'filter' => array("CONTACT_ID" => $contactId),
            'select' => array("ID", "TITLE", "STAGE_ID", "PROBABILITY", "OPPORTUNITY", "CURRENCY_ID")
        )
    );

    // Проверка наличия и успешности получения списка сделок
    if(isset($dealResult['result']) && !empty($dealResult['result'])) {
        echo '<ul>';
        foreach ($dealResult['result'] as $deal) {
            echo '<li>';
            echo 'ID: ' . $deal['ID'] . '<br>';
            echo 'Название: ' . $deal['TITLE'] . '<br>';
            echo 'Стадия: ' . $deal['STAGE_ID'] . '<br>';
            echo 'Вероятность: ' . $deal['PROBABILITY'] . '%<br>';
            echo 'Сумма: ' . $deal['OPPORTUNITY'] . ' ' . $deal['CURRENCY_ID'] . '<br>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo 'Нет данных о сделках, связанных с этим контактом.';
    }
} else {
    echo 'Контакт не найден.';
}
?>