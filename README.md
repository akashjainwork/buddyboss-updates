# buddyboss-updates

### 1. Buddyboss Auto Show/Hide Buddypanel on Mouse Hover

Add Below CSS under your buddyboss child theme

> /wp-content/themes/buddyboss-theme-child/assets/css/custom.css
```
.buddypanel:hover {
    width: 220px;
}
.buddypanel:hover + #page {
    margin-left: 220px !important;
}
.buddypanel:hover .side-panel-menu span {
    width: auto !important;
    opacity: 1 !important;
    visibility: visible !important;
}
.buddypanel:hover + .site>#masthead {
    width: calc(100% - 220px);
}
```

New Update
```
/*Buddy Panel AUTO SHOW/HIDE FIX IF MENU ITEMS ON TOP PANEL ARE MORE THAN 7 */
jQuery('.buddypanel').mouseenter(function () {
       jQuery('.bb-toggle-panel').click();
     });

 jQuery('.buddypanel').mouseleave(function () {
       jQuery('.bb-toggle-panel').click();
     }
 ).mouseleave();
```


#### Preview 
![](/screenshot-auto-show-hide-buddypanel.gif)


### 2. BuddyPress Widget for My Group Option with Group Type filter

> Find Attached File Here bp_my_group.php

