<div class="ui-grid-a">
    <div class="ui-block-a">
        <a href="<?php echo $this->feed->url?>">
            <img src="<?php echo $this->feed->image->url?>" alt="<?php echo $this->feed->image->title?>" title="<?php echo $this->feed->image->title?>"/>
        </a>
    </div>
    <div class="ui-block-b">
    <h3>
        <a href="<?php echo $this->feed->url?>">
            <?php echo $this->feed->title?>
        </a><br/>
        <?php echo $this->languages['last_updated'] . ' : ' . $this->feed->lastUpdated?>
    </h3>
    </div>
</div>
<div data-role="collapsible">
    <h3><?php echo $this->languages['you_are_reading_feed'] . ' : ' . $this->feed->name ?></h3>
    <?php 
    foreach ($this->feed->items as $item)
    {
        ?>
        <div class="feed_element">
            <h4><a href="<?php echo $item->link?>"><?php echo $item->title?></a></h4>
            <p><?php echo $item->description ?></p>
        </div>
        <?php
    }    
    ?>
</div>
