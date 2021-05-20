<?php
$path = dirname(dirname(__FILE__));
$int = (int)$_POST['value'];
require($path . '/function/connection.php');
$cat1 = new \caching\cachingData();
$cache = $cat1->cachingDataCinema();
//arsort($cache[$int]['year_cinema']);
//function cal($a) {
//if (($a['year_cinema'] == "2013") == 1) {
//    return true;
//}
//return false;
//}
//echo "<pre>";
//$cache[(int)$_POST['value']] = array_filter($cache[(int)$_POST['value']], 'cal');
//echo "</pre>";
echo false;
?>



<script>
    document.addEventListener('DOMContentLoaded', () => {

        const getSort = ({ target }) => {
            const order = (target.dataset.order = -(target.dataset.order || -1));
            const index = [...target.parentNode.cells].indexOf(target);
            const collator = new Intl.Collator(['en', 'ru'], { numeric: true });
            const comparator = (index, order) => (a, b) => order * collator.compare(
                a.children[index].innerHTML,
                b.children[index].innerHTML
            );

            for(const tBody of target.closest('table').tBodies)
                tBody.append(...[...tBody.rows].sort(comparator(index, order)));

            for(const cell of target.parentNode.cells)
                cell.classList.toggle('sorted', cell === target);
        };
        document.querySelectorAll('.table_sort thead').forEach(tableTH => tableTH.addEventListener('click', () => getSort(event)));
    });
</script>

<table class="table_sort" id="Result">
    <thead>
    <tr>
        <th class="id_td">id</th>
        <th>Картинка</th>
        <th>Название фильма/Год</th>
        <th>Расчетный балл</th>
        <th>Количество баллов</th>
        <th>Средний балл</th>
        <th>Описание</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($cache[(int)$_POST['value']] as $item): ?>
        <tr>
            <td class="id_td"> <?php echo $item['position'] ?> </td>
            <td> <img src=<?php echo "{$item['path_image']}"; ?>></td>
            <td> <?php echo $item['name_cinema'] ?> </td>
            <td> <?php echo $item['estimated_score'] ?> </td>
            <td> <?php echo $item['voices'] ?> </td>
            <td> <?php echo $item['average_score'] ?> </td>
            <td class="synopsis"> <?php echo $item['synopsis'] ?> </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
