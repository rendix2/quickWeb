<div style="position:absolute; top:0; left0;>
<div id="good" class="good">

{foreach $good as $v}
<div class="animate"><div class="show_good"><div class="show_good_container"><div class="show_good_image"></div></div><div class="show_text">{$v}</div></div></div>
{foreachelse} 
<div class="animate"><div class="show_good"><div class="show_good_container"><div class="show_good_image"></div></div><div class="show_text">{$message}</div></div></div>
{/foreach}
</div>
</div>
