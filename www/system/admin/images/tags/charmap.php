<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Charmap</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<script language="javascript" type="text/javascript" src="charmap.js"></script>
	<link href="../../themes/default/style.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>

<script language="javascript" type="text/javascript">
<!--
function insertChar(chr){
	text = '\&#' + chr + ';';
	opener.document.forms['addnews'].<?php echo $_GET['area']; ?>_story.focus();
	opener.document.forms['addnews'].<?php echo $_GET['area']; ?>_story.value += text;
	opener.document.forms['addnews'].<?php echo $_GET['area']; ?>_story.focus();
}
//-->
</script>

<table align="center" border="0" cellspacing="0" cellpadding="2">
    <tr>
        <td rowspan="2" align="left" valign="top">
            <script language="javascript" type="text/javascript">renderCharMapHTML();</script>
        </td>
        <td width="100" align="center" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100" style="height: 100px">
                <tr>
                    <td class="charmapOver" style="font-size: 40px; height:80px;" id="codeV">&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-size: 10px; font-family: Arial, Helvetica, sans-serif; text-align:center;" id="codeN">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="bottom" style="padding-bottom: 3px;">
            <table width="100" align="center" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td align="center" style="border-left: 1px solid #666699; border-top: 1px solid #666699; border-right: 1px solid #666699;">HTML-Code</td>
                </tr>
                <tr>
                    <td style="font-size: 16px; font-weight: bold; border-left: 1px solid #666699; border-bottom: 1px solid #666699; border-right: 1px solid #666699;" id="codeA" align="center">&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-size: 1px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" style="border-left: 1px solid #666699; border-top: 1px solid #666699; border-right: 1px solid #666699;">NUM-Code</td>
                </tr>
                <tr>
                    <td style="font-size: 16px; font-weight: bold; border-left: 1px solid #666699; border-bottom: 1px solid #666699; border-right: 1px solid #666699;" id="codeB" align="center">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>