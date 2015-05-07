<?php
/**
 * @version          $Id: index.php 20196 2011-01-09 02:40:25Z ian $
 * @package          Joomla.Site
 * @copyright        Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license          GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die; 
$path = $this->baseurl.'/templates/'.$this->template;

JHTML::_('behavior.framework', true);


// get params

$app        = JFactory::getApplication();
$templateparams    = $app->getTemplate(true)->params;
$showLeftColumn = ($this->countModules('left'));
$showRightColumn = ($this->countModules('right'));
$showuser3 = ($this->countModules('user3'));
$showuser4 = ($this->countModules('user4'));
$showuser5 = ($this->countModules('user5'));
$showuser6 = ($this->countModules('user6'));
$showuser8 = ($this->countModules('user8'));
$showuser9 = ($this->countModules('user9'));
$showuser10 = ($this->countModules('user10'));
$showFeatured = ($this->countModules('user2'));
$showNew = ($this->countModules('new'));
$showSpecials = ($this->countModules('specials'));

if (isset($_GET['view'])) {$opt_content = $_GET['view'];} else {$opt_content="no_content";}
if (isset($_GET['layout'])) {$Edit = $_GET['layout'];} else {$Edit="no_edit";}
if (isset($_GET['option'])) {$option = $_GET['option'];}

$menus      = &JSite::getMenu();
$menu      = $menus->getActive();
$pageclass   = "";

if (is_object( $menu )) : 
$params1 =  $menu->params;
$pageclass = $params1->get( 'pageclass_sfx' );
endif; 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<!--[if lte IE 7]> <html class="ie7"> <![endif]-->  
<!--[if IE 8]>     <html class="ie8"> <![endif]--> 
<jdoc:include type="head" />
<link href='http://fonts.googleapis.com/css?family=Oswald&v1' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Allura' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $path ?>/css/position.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="<?php echo $path ?>/css/layout.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="<?php echo $path ?>/css/print.css" type="text/css" media="Print" />
<link rel="stylesheet" href="<?php echo $path ?>/css/virtuemart.css" type="text/css"  />
<link rel="stylesheet" href="<?php echo $path ?>/css/products.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $path ?>/css/personal.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $path ?>/css/template.css?v=2.3" type="text/css" />
<style>
.img-indent , .main-top {
 behavior:url(<?php echo $path ?>/PIE.php);
}
</style>

<!--[if lt IE 8]>
    <div style=' display:none; clear: both; text-align:center; position: relative; z-index:9999;'>
        <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://www.theie6countdown.com/images/upgrade.jpg" border="0" &nbsp;alt="" /></a>
    </div>
<![endif]-->
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo $path ?>/javascript/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo $path ?>/javascript/slides.min.jquery.js"></script>
<script type="text/javascript" src="<?php echo $path ?>/javascript/jquery_carousel_lite.js"></script>
<script type="text/javascript" src="<?php echo $path ?>/javascript/jquery.jqzoom-core.js"></script>
<script type="text/javascript" src="<?php echo $path ?>/javascript/jquery.equalheights.js"></script>
<script type="text/javascript" src="<?php echo $path ?>/javascript/jqtransform.js"></script>
<script type="text/javascript" src="<?php echo $path ?>/javascript/cookie.js"></script>
<script type="text/javascript" src="<?php echo $path ?>/javascript/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php echo $path ?>/javascript/script.js"></script>
<script type="text/javascript" src="<?php echo $path ?>/javascript/menu.js"></script>
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(window).load(function(){
 // hide #back-top first
 $j("#back-top").hide();
 // fade in #back-top
 $j(function () {
  //$j(window).scroll(function () {
  // if ($j(this).scrollTop() > 200) {
   // $j('#back-top').fadeIn();
   //} else {
   // $j('#back-top').fadeOut();
  // }
 // });
  // scroll body to 0px on click
  $j('#back-top a').click(function () {
   $j('body,html').animate({
    scrollTop: 0
   }, 800);
   return false;
  });
 });
});

$j(document).ready(function() {
              
    $j(".vmgroup_new2 ul li .product-box").hover(function() {
      $j(this).find("div.fleft").stop().animate({ top: 0}, 500 , "easeOutBack");
    },function(){
      $j(this).find("div.fleft").stop().animate({top: -250}, 500 , "easeInBack");
    });         

    $j(function(){
       $j('#select-form').jqTransform({imgPath:'<?php echo $path ?>/images/'}).css('display','block');
    });
    if($j(".category-title ").length){
    $j(".category-title").equalHeights()}
    if($j(".vmproduct_new .product-box").length){
    $j(".vmproduct_new .product-box").equalHeights()}
    if($j(".column").length){
    $j(".column").equalHeights()}
    if($j(".column2").length){
    $j(".column2").equalHeights()}
    
});
$j(document).ready(function() {
  var vmcartck = $j('.vmCartModule');
  vmcartck.top = vmcartck.offset().top;
  vmcartck.left = vmcartck.offset().left;
  $j('.cart-click').click(function() {
      var el = $j(this);
      var imgtodrag = $j('.product-image:first');
      if (!imgtodrag.length) {
        elparent = el.parent();
        while (!elparent.hasClass('spacer')) {
          elparent = elparent.parent();
        }  
        imgtodrag = elparent.find('img.browseProductImage');
      }
      if (imgtodrag.length) {
        var imgclone = imgtodrag.clone()
          .offset({ top: imgtodrag.offset().top, left: imgtodrag.offset().left })
          .css({'opacity': '0.7', 'position': 'absolute' , 'background': '#fff', 'height':'150px' , 'width': '150px','z-index': '10000'})
          .appendTo($j('body'))
          .animate({
            'top': vmcartck.top+10,
            'left': vmcartck.left+30,
            'width':55,
            'height':55
          },600, 'linear');
        imgclone.animate({
          'width': 0,
          'height': 0
        });
      }
  });              
});
$j(document).ready(function() {
  var vmcartck = $j('.vmCartModule');
  vmcartck.top = vmcartck.offset().top;
  vmcartck.left = vmcartck.offset().left;
  $j('.cart-click2').click(function() {
      var el = $j(this);
      var imgtodrag = $j('#carousel li.current  .productimage , .productimage2');
      
      if (imgtodrag.length) {
        var imgclone = imgtodrag.clone()
          .offset({ top: imgtodrag.offset().top, left: imgtodrag.offset().left })
          .css({'opacity': '0.7', 'position': 'absolute' , 'background': '#fff' , 'height':'150px' , 'width': '150px','z-index': '10000'})
          .appendTo($j('body'))
          .animate({
            'top': vmcartck.top+10,
            'left': vmcartck.left+30,
            'width':55,
            'height':55
          },600, 'linear');
        imgclone.animate({
          'width': 0,
          'height': 0
        });
      }
  });              
});

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44743963-1', '99ms.com.sg');
  ga('send', 'pageview');

</script>
  <meta name="google-site-verification" content="2aLY1jmowirDj0_8gxWTYklAs_WC8CPksPuZfRyYsPQ" />
</head>
<?php
$menu = &JSite::getMenu();
if ($menu->getActive() == $menu->getDefault()) {
    $body_class = 'first';
}else{
    $body_class = 'all';
}
?>
<body class="<?php echo $body_class." ".$pageclass;?>">
<div class="main">
    <div id="header">
      <!--  <p>Подправить страницу категорий товаров,при поиске видны категории</p>-->
        <div id="logo">
          <jdoc:include type="modules" name="logo" />
        </div>
        <?php if ($showuser6) : ?>
          <div class="cart">
            <jdoc:include type="modules" name="user6" style="xhtml" />
          </div>
        <?php endif; ?>
                 
                <?php if ($showuser5) : ?>
                    <div class="currency">
                        <jdoc:include type="modules" name="user5" style="xhtml" />
                    </div>
                <?php endif; ?>
        <div class="head-row">
          <div class="relative">
            <?php if ($showuser3) : ?>
              <div id="topmenu">
                <jdoc:include type="modules" name="user3" style="xhtml" />
              </div>
            <?php endif; ?>
            <?php if ($showuser4) : ?>
              <div id="search">
                <jdoc:include type="modules" name="user4" style="xhtml" />
              </div>
            <?php endif; ?>
          </div>
        </div>
                <jdoc:include type="modules" name="user10" style="xhtml" />
        <jdoc:include type="modules" name="user7" style="xhtml"/>
      </div>
    <!-- END header -->
  <div id="content">
    <jdoc:include type="modules" name="user8" style="user"/>
      <div class="wrapper2">
        <?php if ($showLeftColumn): ?>
        <div id="left">
          <div class="wrapper2">
            <div class="extra-indent">
              <jdoc:include type="modules" name="left" style="left" />
              
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php if ($showRightColumn) : ?>
        <div id="right">
          <div class="wrapper">
            <div class="extra-indent">
              <jdoc:include type="modules" name="right" style="user" />
            </div>
          </div>
        </div>
        <?php endif; ?>
        <div class="container">
        <?php if (($showFeatured ) && ($option!="com_search") ) { ?>
          <?php if ($this->getBuffer('message')) : ?>
            <div class="error err-space">
              <jdoc:include type="message" />
            </div>
          <?php endif; ?>
          <jdoc:include type="modules" name="user2" style="user" />
        <?php } else { ?>
          <?php if ($this->getBuffer('message')) : ?>
            <div class="error err-space">
              <jdoc:include type="message" />
            </div>
          <?php endif; ?>
          <div class="content-indent">
            <jdoc:include type="component" />
          </div>
        <?php }; ?>
      </div><div class="clear"></div>
    </div>
    <jdoc:include type="modules" name="new" style="new" />
  </div>
  <div class="clear"></div>
    <div id="foot">
     <?php if ($showuser9) : ?>
    <div class="wrapper">
      <jdoc:include type="modules" name="user9" style="new" />
    </div>  
    <?php endif; ?>
      <div class="space">
        <div class="wrapper">
          <div class="footerText">
            <jdoc:include type="modules" name="footer" />
          </div>
        </div>
      </div>
      <div class="space2">
        <div class="wrapper">
          <div class="footer-2">
            <jdoc:include type="modules" name="footer-2" />
          </div>
        </div>
      </div>
      <p id="back-top">
        <a href="#top"><span></span></a>
      </p>
    </div>
  </div>
  
</body>
</html>
