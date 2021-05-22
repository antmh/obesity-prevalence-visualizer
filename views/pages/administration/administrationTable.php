<h1><?=ucfirst($param)?> Data</h1>
<fieldset class="fieldset-admnistration-size">
    <legend>Data management</legend>
    <div class="administration-data-management">
        <a href="clear?data=<?=$param?>" class="button">Clear</a>
        <a href="insert?data=<?=$param?>" class="button">Insert</a>
        <div class="administration-insert-data">

            <form action="/insert" method="get">
                <?php foreach($table->getHeader() as $column): ?>
                    <?php if($column !== 'Rowid'): ?>
                        <div>
                            <label class="administration-insert-data-label" for="<?=$column?>"><?=$column?></label>
                            <input class="administration-insert-data-input" name="<?=$column?>" id="<?=$column?>">
                        </div>
                    <? endif; ?>
                <?php endforeach; ?>
                <input name="table" type="hidden" value="<?=$param ?>">
                <div>
                    <input class="button button-insert-row" type="submit" />
                </div>
            </form>
        </div>
    </div>
</fieldset>
<h3>Data table</h3>
<?php
include('views/components/table.php');
?>
