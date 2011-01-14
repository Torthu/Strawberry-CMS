<?php
class PhpMyBorder{

  var $width;        // width of the border
  var $fill;         // fillcolor
  var $edge;         // edgecolor
  var $shadow;       // shadowcolor
  var $stylesheet;   // using stylesheet or not
  
  function PhpMyBorder($stylesheet = false){
    $this->setWidth("100%");           // default width
    $this->setFill("DDEEFF");          // default fillcolor
    $this->setEdge("4444AA");          // default edgecolor
    $this->setShadow("888888");        // default shadowcolor
    $this->stylesheet = $stylesheet;   // using stylesheet (default = false)
  }
  
  function setWidth($value){  
    $this->width = trim($value);
  }

  function getWidth(){  
    return $this->width;  
  }

  function setFill($value){  
    $this->fill = trim($value);
  }

  function getFill($prefix = false)  {  
    if(!$prefix) return $this->fill;
    if(strlen($this->fill)<3) return "transparent";
    return strtolower($this->fill) == "transparent" ? "transparent" : "#".$this->fill;
  }

  function setEdge($value)  {  
    $this->edge = trim($value);
  }

  function getEdge($prefix = false)  {  
    if(!$prefix) return $this->edge;
    
    if(  $this->edge===false
        ||
        strlen($this->edge)<3
        ||
        strtolower($this->edge) == "transparent"
    ) return "transparent";
    
    return "#".$this->edge;
  }

  function setShadow($value)  {  
    $this->shadow = trim($value);
  }

  function getShadow($prefix = false)  {  
    if(!$prefix) return $this->shadow;
    if(strlen($this->shadow)<3) return "transparent";
    return strtolower($this->shadow) == "transparent" ? "transparent" : "#".$this->shadow;
  }


  function stylesheet_round(){
?>

.pmb1_b, .pmb1_s {font-size:1px; }
.pmb1_1, .pmb1_2, .pmb1_3, .pmb1_4, .pmb1_b, .pmb1_s {display:block; overflow:hidden;}
.pmb1_1, .pmb1_2, .pmb1_3, .pmb1_s {height:1px;}
.pmb1_2, .pmb1_3, .pmb1_4 {border-style: solid; border-width: 0 1px; }
.pmb1_1 {margin:0 5px; }
.pmb1_2 {margin:0 3px; border-width:0 2px;}
.pmb1_3 {margin:0 2px;}
.pmb1_4 {height:2px; margin:0 1px;}
.pmb1_c {display:block; border-style: solid ; border-width: 0 1px;}
<?php
  }

  function begin_round($width = false, $fill = false, $edge = false){
    if($width)   $this->setWidth  ($width );
    if($fill)    $this->setFill  ($fill);
    if($edge)    $this->setEdge  ($edge);
     ob_start(); 
     if($this->stylesheet){
?>

<!-- begin PhpMyBorder -->
<div style="width:<?php $this->getWidth(true)?>;">
 <b class="pmb1_b">
  <b class="pmb1_1" style="background:<?php echo $this->getEdge(true)?>; color: inherit;"></b>
  <b class="pmb1_2" style="background:<?php echo $this->getFill(true)?>; color: inherit; border-color: <?php echo $this->getEdge(true)?>;"></b>
  <b class="pmb1_3" style="background:<?php echo $this->getFill(true)?>; color: inherit; border-color: <?php echo $this->getEdge(true)?>;"></b>
  <b class="pmb1_4" style="background:<?php echo $this->getFill(true)?>; color: inherit; border-color: <?php echo $this->getEdge(true)?>;"></b>
 </b>
 <div class="pmb1_c" style="background:<?php echo $this->getFill(true)?>; color: inherit; border-color: <?php echo $this->getEdge(true)?>;">
  <b class="pmb1_s"></b>
<?php
    }else{ 
?>

<!-- begin PhpMyBorder -->
<div style="width: <?php echo $this->getWidth(true)?>;">
 <b style="font-size:1px;display:block; overflow:hidden;">
  <b style="background:<?php echo $this->getEdge(true)?>; color: inherit; display:block; overflow:hidden;height:1px;margin:0 5px;"></b>
  <b style="background:<?php echo $this->getFill(true)?>; border-color: <?php echo $this->getEdge(true)?>; color: inherit; display:block; overflow:hidden;height:1px;border-style: solid; border-width: 0 1px;margin:0 3px; border-width:0 2px;"></b>
  <b style="background:<?php echo $this->getFill(true)?>;border-color: <?php echo $this->getEdge(true)?>; color: inherit; display:block; overflow:hidden;height:1px;border-style: solid; border-width: 0 1px;margin:0 2px;"></b>
  <b style="background:<?php echo $this->getFill(true)?>;border-color: <?php echo $this->getEdge(true)?>; color: inherit; display:block; overflow:hidden;border-style: solid; border-width: 0 1px;height:2px; margin:0 1px;"></b>
 </b>
 <div style="background:<?php echo $this->getFill(true)?>; border-color: <?php echo $this->getEdge(true)?>; color: inherit; display:block; border-style: solid ; border-width: 0 1px;">
  <b style="font-size:1px;display:block; overflow:hidden;height:1px;"></b>
<?php 
    }
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
  }

  function end_round(){
    ob_start();

     if($this->stylesheet){
?>

  <b class="pmb1_s"></b>
 </div>
 <b class="pmb1_b">
  <b class="pmb1_4" style="background:<?php echo $this->getFill(true)?>;border-color: <?php echo $this->getEdge(true)?>; color: inherit;"></b>
  <b class="pmb1_3" style="background:<?php echo $this->getFill(true)?>;border-color: <?php echo $this->getEdge(true)?>; color: inherit;"></b>
  <b class="pmb1_2" style="background:<?php echo $this->getFill(true)?>;border-color: <?php echo $this->getEdge(true)?>; color: inherit;"></b>
  <b class="pmb1_1" style="background:<?php echo $this->getEdge(true)?>; color: inherit;"></b>
 </b>
</div>
<!-- end PhpMyBorder -->

<?php }else{?>

  <b style="font-size:1px;display:block; overflow:hidden;height:1px;"></b>
 </div>
 <b style="font-size:1px;display:block; overflow:hidden;">
  <b style="background:<?php echo $this->getFill(true)?>;border-color: <?php echo $this->getEdge(true)?>; color: inherit; display:block; overflow:hidden;border-style: solid; border-width: 0 1px;height:2px; margin:0 1px;"></b>
  <b style="background:<?php echo $this->getFill(true)?>;border-color: <?php echo $this->getEdge(true)?>; color: inherit; display:block; overflow:hidden;height:1px;border-style: solid; border-width: 0 1px;margin:0 2px;"></b>
  <b style="background:<?php echo $this->getFill(true)?>;border-color: <?php echo $this->getEdge(true)?>; color: inherit; display:block; overflow:hidden;height:1px;border-style: solid; border-width: 0 1px;margin:0 3px; border-width:0 2px;"></b>
  <b style="background:<?php echo $this->getEdge(true)?>; color: inherit; display:block; overflow:hidden;height:1px;margin:0 5px;"></b>
 </b>
</div>
<!-- end PhpMyBorder -->

<?php
  }
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
  }
  
  function stylesheet_raised(){
?>

.pmb2_1, .pmb2_2, .pmb2_3, .pmb2_4, .pmb2_5, .pmb2_6, .pmb2_7, .pmb2_8 { overflow:hidden; font-size:1px; display:block; }
.pmb2_1, .pmb2_2, .pmb2_3, .pmb2_6, .pmb2_7, .pmb2_8, .pmb2_s { height:1px; }
.pmb2_2, .pmb2_3, .pmb2_4, .pmb2_5, .pmb2_6, .pmb2_7, .pmb2_c {  border-style: solid; border-width: 0 1px; }
.pmb2_2, .pmb2_3, .pmb2_4, .pmb2_c { border-left-color: #fff; }
.pmb2_7, .pmb2_6, .pmb2_5, .pmb2_c { border-right-color: #999; }
.pmb2_1 { margin:0 5px; background-color: #fff; color: inherit;}
.pmb2_2 { border-right:1px solid #eee; }
.pmb2_3 { border-right:1px solid #ddd; }
.pmb2_4 { border-right:1px solid #aaa; }
.pmb2_5 { border-left:1px solid #eee; }
.pmb2_6 { border-left:1px solid #ddd; }
.pmb2_7 { border-left:1px solid #aaa; }
.pmb2_8 { margin:0 5px; background-color:#999; color: inherit;}
.pmb2_2, .pmb2_7 { margin:0 3px; border-width:0 2px; }
.pmb2_3, .pmb2_6 { margin:0 2px; }
.pmb2_4, .pmb2_5 { margin:0 1px; height:2px; }
.pmb2_c { padding: 0 4px; display:block; }
.pmb2_s {display : block; font-size:1px;}
<?php
  }


  function begin_raised($width = false, $fill = false){
    if($width)   $this->setWidth  ($width );
    if($fill)    $this->setFill  ($fill);
     ob_start(); 
     if($this->stylesheet){
?>

<!-- begin PhpMyBorder -->
<div style="width: <?php echo $this->getWidth(true)?>;">
 <b class="pmb2_1"></b>
 <b class="pmb2_2" style="background: <?php echo $this->getFill(true)?>; color: inherit;"></b>
 <b class="pmb2_3" style="background: <?php echo $this->getFill(true)?>; color: inherit;"></b>
 <b class="pmb2_4" style="background: <?php echo $this->getFill(true)?>; color: inherit;"></b>
 <div class="pmb2_c" style="background: <?php echo $this->getFill(true)?>; color: inherit;">
  <b class="pmb2_s"></b>
<?php  
    }else{ 
?>

<!-- begin PhpMyBorder -->
<div style="width: <?php echo $this->getWidth(true)?>;">
 <b style="overflow:hidden; font-size:1px; display:block;height:1px; margin:0 5px; background:#fff; color: inherit;"></b>
 <b style="background: <?php echo $this->getFill(true)?>; color: inherit; overflow:hidden; font-size:1px; display:block;height:1px; border-style: solid; border-width: 0 1px; border-left-color: #fff; border-right:1px solid #eee; margin:0 3px; border-width:0 2px;"></b>
 <b style="background: <?php echo $this->getFill(true)?>; color: inherit; overflow:hidden; font-size:1px; display:block;height:1px;border-style: solid; border-width: 0 1px;border-left-color: #fff;border-right:1px solid #ddd;margin:0 2px;"></b>
 <b style="background: <?php echo $this->getFill(true)?>; color: inherit; overflow:hidden; font-size:1px; display:block;border-style: solid; border-width: 0 1px; border-left-color: #fff; border-right:1px solid #aaa;margin:0 1px; height:2px;"></b>
 <div style="background: <?php echo $this->getFill(true)?>; color: inherit; padding: 0 4px; display:block;border-style: solid; border-width: 0 1px;border-left-color: #fff;border-right-color: #999;">
  <b style="height:1px; display:block; font-size:1px;"></b>
<?php  
    }
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
  }

  function end_raised(){
    ob_start();

     if($this->stylesheet){
?>      

  <b class="pmb2_s"></b>
 </div>
 <b class="pmb2_5" style="background: <?php echo $this->getFill(true)?>; color: inherit;"></b>
 <b class="pmb2_6" style="background: <?php echo $this->getFill(true)?>; color: inherit;"></b>
 <b class="pmb2_7" style="background: <?php echo $this->getFill(true)?>; color: inherit;"></b>
 <b class="pmb2_8"></b>
</div>  
<!-- end PhpMyBorder -->

<?php
  }else{
?>

  <b style="height:1px; display:block; font-size:1px;"></b>
 </div>
 <b style="background: <?php echo $this->getFill(true)?>; color: inherit; overflow:hidden; font-size:1px; display:block;border-style: solid; border-width: 0 1px;border-right-color: #999;border-left:1px solid #eee; margin:0 1px; height:2px;"></b>
 <b style="background: <?php echo $this->getFill(true)?>; color: inherit; overflow:hidden; font-size:1px; display:block;height:1px;border-style: solid; border-width: 0 1px;border-right-color: #999;border-left:1px solid #ddd; margin:0 2px;"></b>
 <b style="background: <?php echo $this->getFill(true)?>; color: inherit; overflow:hidden; font-size:1px; display:block;height:1px;border-style: solid; border-width: 0 1px;border-right-color: #999;border-left:1px solid #aaa;margin:0 3px; border-width:0 2px;"></b>
 <b style="overflow:hidden; font-size:1px; display:block;height:1px;margin:0 5px; background:#999; color: inherit;"></b>
</div>  
<!-- end PhpMyBorder -->

<?php
  }
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
  }


  function stylesheet_shadow(){
?>

.pmb3_1 { border-width: 1px; border-style: solid; position: relative; left:-3px; top:-3px; }
.pmb3_2 { overflow:hidden; width:100%; padding:0 3px; }
.pmb3_s { height: 1px; font-size: 1px; display: block; }
<?php
  }

  function begin_shadow($width = false, $fill = false, $edge = false, $shadow = false){
    if($width)   $this->setWidth  ($width );
    if($fill)    $this->setFill  ($fill);
    if($edge)    $this->setEdge  ($edge);
    if($shadow) $this->setShadow($shadow);
     ob_start(); 
     if($this->stylesheet){
?>

<!-- begin PhpMyBorder -->
<div style="width: <?php echo $this->getWidth(true)?>; background: <?php echo $this->getShadow(true)?>;">
 <div class="pmb3_1" style="background: <?php echo $this->getFill(true)?>; border-color: <?php echo $this->getEdge(true)?>; color: inherit;">
  <div class="pmb3_2">
   <b class="pmb3_s"></b>
<?php  
    }else{ 
?>

<!-- begin PhpMyBorder -->
<div style="width: <?php echo $this->getWidth(true)?>; background: <?php echo $this->getShadow(true)?>; color: inherit;">
 <div style="background: <?php echo $this->getFill(true)?>; border-color: <?php echo $this->getEdge(true)?>; color: inherit; border-width: 1px; border-style: solid; position: relative; left:-3px; top:-3px;">
  <div style="overflow:hidden; width:100%; padding:0 3px; ">
   <b style="height:1px; display:block; font-size:1px;"></b>
<?php  
    }
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
  }

  function end_shadow(){
    ob_start();

     if($this->stylesheet){
?>

   <b class="pmb3_s"></b>
  </div>
 </div>
</div>
<!-- end PhpMyBorder -->

<?php }else{?>

   <b style="height:1px; display:block; font-size:1px;"></b>
  </div>
 </div>
</div>
<!-- end PhpMyBorder -->

<?php
  }
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
  }

}
?> 