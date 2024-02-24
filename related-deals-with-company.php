<?php
require_once (__DIR__.'/crest.php');

// Получение ID компании из параметра
$companyId = $_GET['id'];

// Получение информации о компании
$result = CRest::call(
    'crm.company.get',
    array(
        'id' => $companyId,
        'select' => array("ID", "TITLE")
    )
);

// Проверка наличия и успешности получения информации о компании
if(isset($result['result'])) {
    $company = $result['result'];
    echo '<h2>Сделки, связанные с компанией "' . $company['TITLE'] . '":</h2>';

    // Запрос к API для получения списка сделок, связанных с этой компанией
    $dealResult = CRest::call(
        'crm.deal.list',
        array(
            'order' => array("STAGE_ID" => "ASC"),
            'filter' => array("COMPANY_ID" => $companyId),
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
        echo 'Нет данных о сделках, связанных с этой компанией.';
    }
} else {
    echo 'Компания не найдена.';
}
?>
