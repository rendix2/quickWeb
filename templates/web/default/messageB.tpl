<div style="position:absolute; top:0; left0;>
<div id="bad" class="bad">

{foreach $bad as $v}
<div class="animate"><div class="show_bad"><div class="show_bad_container"><div class="show_bad_image"></div></div><div class="show_text">{$v}</div></div></div>
{foreachelse} 
<div class="animate"><div class="show_bad"><div class="show_bad_container"><div class="show_bad_image"></div></div><div class="show_text">{$message}</div></div></div>
{/foreach}
</div>
</div>
