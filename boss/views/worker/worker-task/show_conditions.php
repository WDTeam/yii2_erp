<ul>
<?php foreach ($models as $model){?>
    <li>
    <?php echo $model['name'];?>
    <?php echo $model['judge'];?>
    <?php echo $model['value'];?>
    </li>
<?php }?>
</ul>