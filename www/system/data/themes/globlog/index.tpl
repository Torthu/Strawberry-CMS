<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $config['lang']; ?>" lang="<?php echo $config['lang']; ?>" dir="ltr">
<head>
{header}
</head>
<body>

<div id="loading-layer"><img src="system/data/themes/globlog/images/loading.gif" alt=""></div>



<div class="min_width">
	<div class="main">
        <!--header -->
        <div id="header">

{logo-title}

            <div class="side_left_menu">
            	<div class="side_right_menu">
                	<div class="side_top_menu">
                    	<div class="left_top_menu">
                        	<div class="right_top_menu">
                            	<div class="block_search">
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data"><input name="mod" type="hidden" value="search"><input type="text" name="search" class="srup"> <input type="submit" class="srupgo" value="Искать!"></form>
                                </div>
                            	<div class="menu">
                                	
{up-block}
                                    
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--header end-->

        <!--content -->
        <div class="content">
            <div class="side_left">
            	<div class="side_right">
                	<div class="side_top">
                    	<div class="side_bot">
                        	<div class="left_top">
                            	<div class="right_top">
                                	<div class="left_bot">
                                    	<div class="right_bot">
                                        	<div class="indent">
                                                <div class="w100">
												
<div class="column_center">

    <div class="indent_center">
        <div class="side_left_2">
            <div class="side_right_2">
                <div class="side_top_2">
                    <div class="side_bot_2">
                        <div class="left_top_2">
                            <div class="right_top_2">
                                <div class="left_bot_2">
                                    <div class="right_bot_2">
                                        
<div class="indent_center_2">
											                                   
{modules}
							
</div>
										
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
</div>

<div class="side_bar">
    <div class="inside">
<div id="statusbar">
</div> 
{left-block} {right-block}	        
</div>
</div>
 <div class="clear"></div>
      
                                                </div>{down-block}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--content end-->
   
        <!--footer -->
          <div id="footer">
{copyrights-block}
        </div>
        <!--footer end-->	
    </div>
</div>	
</body>
</html>
