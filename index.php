<?php
declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;
const OPERATION_RENAME = 4;
const OPERATION_ADDQUANTITY = 5;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
    OPERATION_RENAME => OPERATION_RENAME . '. Изменить название товара.',
    OPERATION_ADDQUANTITY => OPERATION_ADDQUANTITY . '. Добавить количество товара.'
];

$items = [];

function localPrint($items): void {
    foreach ( $items as $item ) {
        echo (array_search($item, $items)) . '.'. PHP_EOL;
        foreach ( $item as $key => $value2 ) {
            echo "  " . "$key : $value2" . PHP_EOL;
        }
        echo "\n";
    }
    echo "***<конец списка>*** \n";
}

function  totalListPrint (?array $items, bool $delListMarker = false): void {

    if ($delListMarker) {
        echo 'Текущий список покупок:' . PHP_EOL;
        echo "\n";
        localPrint($items);
    } else {
        if (count($items)) {
            echo 'Ваш список покупок: ' . PHP_EOL;
            echo "\n";
            localPrint($items);
        } else {
            $supportText = 'Ваш список покупок пуст.' . PHP_EOL;
            echo $supportText . "\n";
        }
        echo "\n";
    }
}

function add(array &$items): void {
    echo "Введите название товара для добавления в список: \n> ";
    $nameSubElement = trim(fgets(STDIN));
    echo "Введите количество для добавленного товара: \n> ";
    $quantitySubElement = trim(fgets(STDIN));
    $fullElement = array(
        "name" => $nameSubElement,
        "quantity" => (int)$quantitySubElement
    );
    $items[] = $fullElement;
}

function del(array &$items): void {
    totalListPrint($items, true);

    echo 'Введите название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));
    foreach ( $items as $item ) {
        $delKey = array_search($item, $items);
        if (in_array($itemName, $item, true) !== false) {
            unset($items[$delKey]);
            break;
        }
    }
}

function rname(array &$items): void {
    totalListPrint($items, true);

    echo 'Введите название товара, который хотите переименовать:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));
    foreach ( $items as $item ) {
        $renameKey = (int)array_search($item, $items);
        if (in_array($itemName, $item, true) !== false) {
            echo 'Введите новое название товара:' . PHP_EOL . '> ';
            $newItemName = trim(fgets(STDIN));
            $items[$renameKey]['name'] = $newItemName;
            break;
        }
    }
}

function addQantity(array &$items): void {
    totalListPrint($items, true);

    echo 'Введите название товара, количество которого хотите добавить:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));
    foreach ( $items as $item ) {
        $renameKey = (int)array_search($item, $items);
        if (in_array($itemName, $item, true) !== false) {
            echo 'Введите добавляемое количество товара:' . PHP_EOL . '> ';
            $newItemQuantity = (int)trim(fgets(STDIN));
            $items[$renameKey]['quantity'] += $newItemQuantity;
            break;
        }
    }
}

function prnt (array $items): void {
    totalListPrint($items);
    echo 'Всего ' . count($items) . ' позиций. '. PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}

function menuCorrection (array $items, array &$operations, int $menuNumber): void {

    $menuComment = '';

    switch ($menuNumber) {
        case 2:
            $menuComment = OPERATION_DELETE . '. Удалить товар из списка покупок.';
            break;

        case 4:
            $menuComment = OPERATION_RENAME . '. Изменить название товара.';
            break;

        case 5:
            $menuComment = OPERATION_ADDQUANTITY . '. Добавить количество товара.';
            break;
    }

    if ((count($items) !== 0) && (!array_key_exists($menuNumber, $operations))) {
        array_splice($operations, $menuNumber, 0, $menuComment);
    }

    if ((count($items) === 0) && (array_key_exists($menuNumber, $operations))) {
        unset($operations[$menuNumber]);
    }
}

do {
    //system('clear');
    system('cls'); // windows

    do {

        totalListPrint($items);

        echo 'Выберите операцию для выполнения: ' . PHP_EOL;

        menuCorrection ($items, $operations, 2);
        menuCorrection ($items, $operations, 4);
        menuCorrection ($items, $operations, 5);

        echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
        $operationNumber = trim(fgets(STDIN));

        if (!array_key_exists($operationNumber, $operations)) {
            system('clear');

            if (($operationNumber === '2') || ($operationNumber === '4') || ($operationNumber === '5')) {
                echo '!!! Нет объектов для обработки. Выберите другую операцию!' . PHP_EOL;
            } else {
                echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
            }
        }

    } while (!array_key_exists($operationNumber, $operations));

    echo 'Выбрана операция: '  . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            add($items);
            break;

        case OPERATION_DELETE:
            del($items);
            break;

        case OPERATION_PRINT:
            prnt($items);
            break;

        case OPERATION_RENAME:
            rname($items);
            break;

        case OPERATION_ADDQUANTITY:
            addQantity($items);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;