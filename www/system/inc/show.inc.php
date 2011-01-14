<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/**
 * @package Show
 * @access private
 */

//do {



///////////////////////////////////////

if (!empty($allow_active_news) or !empty($allow_full_story)){
include root_directory."/inc/show.news.php";
}

///////////////////////////////////////



if (!empty($config['use_comm'])) {

    $allow_comments  = run_filters('allow-comments', $allow_comments);
    $allow_comment_form = run_filters('allow-comment-form', $allow_comment_form);


  if (!empty($allow_comment_form)) {
    include_once root_directory."/inc/show.comment-form.php"; 
  } 
  
  if (!empty($allow_comments)) {
  
    echo "<a name=\"comments\"></a>";
    echo !empty($config['cajax']) ? "<span id=\"commentslist\">" : "";
    include_once root_directory."/inc/show.comments.php";
    echo !empty($config['cajax']) ? "</span>" : "";












if (!empty($config['cnumber']) and !empty($post['comments']) and $config['cnumber'] < $post['comments']) {
$count = $comm_b;
############## PAGES
//echo pcp("comments", ((!empty($skip) and is_numeric($skip)) ? $skip : 0), ((!empty($number) and is_numeric($number)) ? $number : 0), $tpl['template'], ((!empty($where) and is_numeric($where)) ? $where : ""));
############## PAGES
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // << Previous & Next >>
$cprev_next_msg = !empty($template_cprev_next) ? $template_cprev_next : "";

//----------------------------------
// Previous link
//----------------------------------
if ($cpage){
    $tpl['prev-next']['prev-link'] = straw_get_link(array_merge($post, array('cpage' => ($cpage - $config['cnumber']))), 'cpage');
} else {
    $tpl['prev-next']['prev-link'] = '';
    $no_cprev = true;
}

//----------------------------------
// Pages
//----------------------------------



if ($config['cnumber']){
    $pages_count   = @ceil($count / $config['cnumber']);
    $pages_cpage   = 0;
    $pages         = array();
    $pages_section = (int)$config['cpages_section'];
    $pages_break   = (int)$config['cpages_break'];

    if ($pages_break and $pages_count > $pages_break){
        for ($j = 1; $j <= $pages_section; $j++){
            if ($pages_cpage != $cpage){
            	$pages[] = '<a href="'.straw_get_link(array_merge($post, array('cpage' => $pages_cpage)), 'cpage').'#comments_list">'.$j.'</a>';
            } else {
            	$pages[] = '<b>'.$j.'</b>';
            }

            $pages_cpage += $config['cnumber'];
        }

        if (((($cpage / $config['cnumber']) + 1) > 1) and ((($cpage / $config['cnumber']) + 1) < $pages_count)){
            $pages[] = ((($cpage / $config['cnumber']) + 1) > ($pages_section + 2)) ? '...' : '';
            $page_min = ((($cpage / $config['cnumber']) + 1) > ($pages_section + 1)) ? ($cpage / $config['cnumber']) : ($pages_section + 1);
            $page_max = ((($cpage / $config['cnumber']) + 1) < ($pages_count - ($pages_section + 1))) ? (($cpage / $config['cnumber']) + 1) : $pages_count - ($pages_section + 1);

            $pages_cpage = ($page_min - 1) * $config['cnumber'];

            for ($j = $page_min; $j < $page_max + ($pages_section - 1); $j++){
                if ($pages_cpage != $cpage) {
                	$pages[] = '<a href="'.straw_get_link(array_merge($post, array('cpage' => $pages_cpage)), 'cpage').'#comments_list">'.$j.'</a>';
                } else {
                	$pages[] = '<b>'.$j.'</b>';
                }

                $pages_skip += $config['cnumber'];
            }

            $pages[] = ((($cpage / $config['cnumber']) + 1) < $pages_count - ($pages_section + 1)) ? '...' : '';
        } else {
        	$pages[] = '...';
        }

        $pages_cpage = ($pages_count - $pages_section) * $config['cnumber'];

        for ($j = ($pages_count - ($pages_section - 1)); $j <= $pages_count; $j++){
            if ($pages_cpage != $cpage){
            	$pages[] = '<a href="'.straw_get_link(array_merge($post, array('cpage' => $pages_cpage)), 'cpage').'#comments_list">'.$j.'</a>';
            } else {
            	$pages[] = '<b>'.$j.'</b>';
            }

            $pages_cpage += $config['cnumber'];
        }
    } else {
         for ($j = 1; $j <= $pages_count; $j++){
            if ($pages_cpage != $cpage){
            	$pages[] = '<a href="'.straw_get_link(array_merge($post, array('cpage' => $pages_cpage)), 'cpage').'#comments_list">'.$j.'</a>';
            } else {
            	$pages[] = ' <b>'.$j.'</b> ';
            }

            $pages_cpage += $config['cnumber'];
        }
    }

    $tpl['prev-next']['pages']        = join(' ', $pages);
    $tpl['prev-next']['current-page'] = (($cpage + $config['cnumber']) / $config['cnumber']);
    $tpl['prev-next']['total-pages']  = $pages_count;
}

//----------------------------------
// Next link
//----------------------------------
if ($cpage + $config['cnumber'] < $count){
	$tpl['prev-next']['next-link'] = straw_get_link(array_merge($post, array('cpage' => ($cpage + $config['cnumber']))), 'cpage')."#comments_list";
} else {
    $tpl['prev-next']['next-link'] = '';
    $no_cnext = true;
}

if (!$no_cprev or !$no_cnext){
	include templates_directory.'/'.$tpl['template'].'/cprev_next.tpl';
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
     
     
     
     
     
     
     
     

  }

}
unset($tpl);
//} while (0);
?>