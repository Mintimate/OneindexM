<?php view::layout('themes/'.(config('style')?config('style'):'material').'/layout')?>

<?php view::begin('content');?>
    <div class="mdui-typo-display-2" style="margin: 100px auto;text-align: center;"><?php echo $tip ?></div>
<?php view::end('content');?>