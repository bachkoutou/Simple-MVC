        <ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
        <li data-role="list-divider"><?php echo $this->languages['list']?></li>
        <?php 
        if (count($this->feeds)) 
        {
            foreach ($this->feeds as $feed)
            {
        ?>
            <li><a href="/?controller=Feed&action=detail&id=<?php echo $feed->id?>"><?php echo $feed->name;?></a></li>
        <?php }
        } else { ?>

        <li><?php echo $this->languages['no_feeds']?></li>
        <?php }?>
        </ul>
