<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $this->language['templates']['feed']['title']?></title>
       <?php $this->renderCss();?>
       <?php $this->renderJs();?>
    </head>
<body>
<div data-role="page">
    <?php 
        $this->render('header', 'Main');
        $this->renderMessages();
        $this->main();
        $this->render('footer', 'Main'); 
    ?>
</div>
</body>
</html>
