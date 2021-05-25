<h1><?=ucfirst($param)?> Data</h1>
<fieldset class="fieldset-admnistration-size">
    <legend>Data management</legend>
    <div class="administration-data-management">
        <a href="?data=<?=$param?>" class="button" id="clear-data">Clear</a>
        <a href="insert?data=<?=$param?>" class="button">Insert</a>
        <div class="administration-insert-data">
            <form id="insert-data-form" method="post">
                <?php foreach($columns as $column): ?>
                    <div>
                        <label class="administration-insert-data-label" for="<?=$column?>"><?=$column?></label>
                        <input class="administration-insert-data-input" name="<?=$column?>" id="<?=$column?>">
                    </div>
                <?php endforeach; ?>
                <input class="button button-insert-row" type="submit" value="Insert row"/>
            </form>
        </div>
    </div>
</fieldset>
<h3>Data table</h3>
<?php
include('views/components/administrationTable.php');
?>
<script src="/js/administration.js"></script>