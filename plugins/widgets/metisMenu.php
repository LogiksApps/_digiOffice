<?php
if(!defined('ROOT')) exit('No direct script access allowed');

loadModuleLib("navigator","api");

$menuTree1=generateNavigationFromDB("default","links","app");

$menuTree2=generateNavigationFromDir(APPROOT."misc/menus/","app");

$menuTree=array_merge_recursive($menuTree1,$menuTree2);
$reportsTree=[];

foreach ($menuTree as $category=>$menuSet) {
  foreach ($menuSet as $key => $menu) {
    if($menu['category']!=null && strlen($menu['category'])>0) {
      unset($menuTree[$category][$key]);
      $menuTree[$category][$menu['category']][$key]=$menu;
    }
  }
}
if(isset($menuTree['Reports'])) {
    $reportsTree=$menuTree['Reports'];
} elseif(isset($menuTree['reports'])) {
    $reportsTree=$menuTree['reports'];
}
// printArray($menuTree);
// printArray($reportsTree);
?>
<ul id="sidebarTabLeft" class="nav nav-tabs nav-justified" data-tabs="tabs">
    <li role="presentation" class="active"><a href="#sidebarMenu" data-toggle="tab"><i class="fa fa-vcard-o fa-fw"></i>
    	&nbsp;Suite</a></li>
    <li role="presentation"><a href="#sidebarReports" data-toggle="tab"><i class="fa fa-line-chart fa-fw"></i>
    	&nbsp;Reports</a></li>
</ul>
<div id="sidebarPaneLeft" class="tab-content">
    <div id='sidebarMenu' class='tab-pane active'>
<ul class="metismenu" id="metismenu">
  <?php
    foreach ($menuTree as $category=>$menuSet) {
      if(count($menuSet)<=0 || strlen($category)<=0 || strtolower($category)=="reports") continue;
      $hash=md5($category);

      $html="<li class='menuGroup'>";
      $html.="<a href='#' aria-expanded='false'>$category <span class='fa arrow'></span></a>";
      $html.="<ul aria-expanded='false' class='secondary collapse'>";

      $html1="";
      foreach ($menuSet as $key => $menu) {
        if(is_numeric($key)) {
          $more=[];
          if($menu['target']!=null && strlen($menu['target'])>0) {
            $more[]="target='{$menu['target']}'";
          }
          if($menu['class']!=null && strlen($menu['class'])>0) {
            $more[]="class='menuItem {$menu['class']}'";
          } else {
            $more[]="class='menuItem'";
          }
          if($menu['category']!=null && strlen($menu['category'])>0) {
            $more[]="data-category='{$menu['category']}'";
          }
          if($menu['tips']!=null && strlen($menu['tips'])>0) {
            $more[]="title='{$menu['tips']}'";
          }

          if($menu['iconpath']!=null && strlen($menu['iconpath'])>0) {
            $html1.="<li><a href='{$menu['link']}' ".implode(" ", $more)."><i class='menuIcon {$menu['iconpath']}'></i>&nbsp; {$menu['title']}</a></li>";
          } else {
            $html1.="<li><a href='{$menu['link']}' ".implode(" ", $more).">{$menu['title']}</a></li>";
          }
        } else {
          $keyS=toTitle($key);
          $html.="<li class='menuGroup'>";
          $html.="<a href='#' aria-expanded='false'>$keyS <span class='fa arrow'></span></a>";
          $html.="<ul aria-expanded='false' class='secondary collapse'>";

          foreach ($menu as $key1 => $menu1) {
            $more=[];
            if($menu1['target']!=null && strlen($menu1['target'])>0) {
              $more[]="target='{$menu1['target']}'";
            }
            if($menu1['class']!=null && strlen($menu1['class'])>0) {
              $more[]="class='menuItem {$menu1['class']}'";
            } else {
              $more[]="class='menuItem'";
            }
            if($menu1['category']!=null && strlen($menu1['category'])>0) {
              $more[]="data-category='{$menu1['category']}'";
            }
            if($menu1['tips']!=null && strlen($menu1['tips'])>0) {
              $more[]="title='{$menu1['tips']}'";
            }

            if($menu1['iconpath']!=null && strlen($menu1['iconpath'])>0) {
              $html.="<li><a href='{$menu1['link']}' ".implode(" ", $more)."><i class='menuIcon {$menu1['iconpath']}'></i>&nbsp; {$menu1['title']}</a></li>";
            } else {
              $html.="<li><a href='{$menu1['link']}' ".implode(" ", $more).">{$menu1['title']}</a></li>";
            }
          }

          $html.="</ul>";
          $html.="</li>";
        }
      }
      $html.=$html1;
      $html.="</ul>";
      $html.="</li>";

      echo $html;
    }
  ?>
</ul>
    </div>
    <div id='sidebarReports' class='tab-pane'>
<ul class="metismenu" id="metismenu1">
  <?php
    // printArray($reportsTree);
    $htmlLast="";
    $html="";
    foreach ($reportsTree as $category=>$menuSet) {
      if(count($menuSet)<=0 || strlen($category)<=0) continue;
    
      $hash=md5($category);

      if(is_numeric($category)) {
            $menu1=$menuSet;
            $more=[];
            if($menu1['target']!=null && strlen($menu1['target'])>0) {
              $more[]="target='{$menu1['target']}'";
            }
            if($menu1['class']!=null && strlen($menu1['class'])>0) {
              $more[]="class='menuItem {$menu1['class']}'";
            } else {
              $more[]="class='menuItem'";
            }
            if($menu1['category']!=null && strlen($menu1['category'])>0) {
              $more[]="data-category='{$menu1['category']}'";
            }
            if($menu1['tips']!=null && strlen($menu1['tips'])>0) {
              $more[]="title='{$menu1['tips']}'";
            }

            if($menu1['iconpath']!=null && strlen($menu1['iconpath'])>0) {
              $htmlLast.="<li><a href='{$menu1['link']}' ".implode(" ", $more)."><i class='menuIcon {$menu1['iconpath']}'></i>&nbsp; {$menu1['title']}</a></li>";
            } else {
              $htmlLast.="<li><a href='{$menu1['link']}' ".implode(" ", $more).">{$menu1['title']}</a></li>";
            }
      } else {
          $html="<li class='menuGroup'>";
          $html.="<a href='#' aria-expanded='false'>$category <span class='fa arrow'></span></a>";
          $html.="<ul aria-expanded='false' class='secondary collapse'>";
          $html1="";
          foreach ($menuSet as $key => $menu) {
            if(is_numeric($key)) {
              $more=[];
              if($menu['target']!=null && strlen($menu['target'])>0) {
                $more[]="target='{$menu['target']}'";
              }
              if($menu['class']!=null && strlen($menu['class'])>0) {
                $more[]="class='menuItem {$menu['class']}'";
              } else {
                $more[]="class='menuItem'";
              }
              if($menu['category']!=null && strlen($menu['category'])>0) {
                $more[]="data-category='{$menu['category']}'";
              }
              if($menu['tips']!=null && strlen($menu['tips'])>0) {
                $more[]="title='{$menu['tips']}'";
              }
    
              if($menu['iconpath']!=null && strlen($menu['iconpath'])>0) {
                $html1.="<li><a href='{$menu['link']}' ".implode(" ", $more)."><i class='menuIcon {$menu['iconpath']}'></i>&nbsp; {$menu['title']}</a></li>";
              } else {
                $html1.="<li><a href='{$menu['link']}' ".implode(" ", $more).">{$menu['title']}</a></li>";
              }
            } else {
              $keyS=toTitle($key);
              $html.="<li class='menuGroup'>";
              $html.="<a href='#' aria-expanded='false'>$keyS <span class='fa arrow'></span></a>";
              $html.="<ul aria-expanded='false' class='secondary collapse'>";
    
              foreach ($menu as $key1 => $menu1) {
                $more=[];
                if($menu1['target']!=null && strlen($menu1['target'])>0) {
                  $more[]="target='{$menu1['target']}'";
                }
                if($menu1['class']!=null && strlen($menu1['class'])>0) {
                  $more[]="class='menuItem {$menu1['class']}'";
                } else {
                  $more[]="class='menuItem'";
                }
                if($menu1['category']!=null && strlen($menu1['category'])>0) {
                  $more[]="data-category='{$menu1['category']}'";
                }
                if($menu1['tips']!=null && strlen($menu1['tips'])>0) {
                  $more[]="title='{$menu1['tips']}'";
                }
    
                if($menu1['iconpath']!=null && strlen($menu1['iconpath'])>0) {
                  $html.="<li><a href='{$menu1['link']}' ".implode(" ", $more)."><i class='menuIcon {$menu1['iconpath']}'></i>&nbsp; {$menu1['title']}</a></li>";
                } else {
                  $html.="<li><a href='{$menu1['link']}' ".implode(" ", $more).">{$menu1['title']}</a></li>";
                }
              }
    
              $html.="</ul>";
              $html.="</li>";
            }
          }
          $html.=$html1;
          $html.="</ul>";
          $html.="</li>";
      }

      echo $html;
    }
    echo $htmlLast;
  ?>
</ul>
    </div>
</div>

<script type="text/javascript">
$(function() {
  $("#sidebarLeft").delegate("a.menuItem[href]","click",function(e) {
        e.preventDefault();

        ttl=$(this).text();
        href=$(this).attr("href");
        target=$(this).attr("target");

        if(target==null || $(this).attr("target").length<=0) {
          if(href.indexOf("http://")===0 || href.indexOf("https://")===0) {
            openLinkFrame(ttl,href);
          } else {
            openLinkFrame(ttl,_link(href));
          }

          if(window.screen.width<window.screen.height && window.screen.width<767) {
            $("#sidebarLeft").removeClass("open");
            $("#page-wrapper").toggleClass("openSidebar");
          }
        } else if(target=="top") {
          window.top.location=href;
        } else if(target=="_blank") {
          window.open(href);
        } else if(target.substr(0,1)=="_") {
          window.open(href,target);
        } else {
          openLinkFrame(ttl,href);
        }
    });

  //$("#sidebarLeft").addClass("open");
});
</script>
