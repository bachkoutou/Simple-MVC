<div>
    <div class="headerDate">
    <?php echo date("D M j G:i:s T Y");  ?>
     | <a href="<?php echo PREVIEW_FRONT_URL?>" target="_blank"> Preview Site</a>
    </div>
    <div class="headerMenu">
      <?php echo "Welcome : " . ucfirst(sessionManager::get('login')) . " | ";?><a href="/?controller=user&action=list">Manage Users</a> | <a href="/?controller=user&action=disconnect">Disconnect</a>
    </div>
</div>    
