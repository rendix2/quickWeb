<div class="foot">
<div style="font-size:12px" align="center">
    <div><span class="copy">© 2014 QuickWEB.cz - All rights reserved.</span> Designed by Roman Liberda. Programmed by Tomáš Babický.</div>
    {if $DEBUG == 1}
    {$lang.query_count_db|sprintf:$pf.pg_query}<br>
    {$lang.higgest_ram|sprintf:$pf.used_ram[0] : $pf.used_ram[1] : $pf.used_ram[2]}<br>
    {$lang.generation_page_time|sprintf:$pf.generation_time}<br>
    {$lang.php_version}<strong>{$php_version}</strong></div>
    {/if}
</div>
<div class="loading"><div class="bar"></div></div>
<div id="box_cont"><div id="box"></div></div>
{literal}
<script>
$(document).ready(function() { 
$('.show_good').hide();  
$('.show_good').each(function(i) {
   $(this).delay(500).delay(150*i).fadeIn(300);
   });
$('.animate').each(function(i) {
   $(this).delay(5000).delay(500*i).animate({
            height: "toggle",
            opacity: "toggle"
        }, "slow");
});
});
$(document).ready(function() { 
  $('#selectCatMain').hide();

});
$(".open_cats").click(function(e){
    $("#selectCatMain").slideDown( 150 );
     e.stopPropagation();
});

$("#selectCatMain").click(function(e){
    e.stopPropagation();
});

$(document).click(function(){
    $("#selectCatMain").slideUp( 150 );
});
$(document).ready(function() { 
  $('#selectPagesMain').hide();

});
$(".open_pages").click(function(e){
    $("#selectPagesMain").slideDown( 150 );
     e.stopPropagation();
});

$("#selectPagesMain").click(function(e){
    e.stopPropagation();
});

$(document).click(function(){
    $("#selectPagesMain").slideUp( 150 );
});
    
    
    $("#articleText")
  .focus(function() {
        if (this.value === this.defaultValue) {
            this.value = '';
        }
  })
  .blur(function() {
        if (this.value === '') {
            this.value = this.defaultValue;
        }
        
});
</script>
<script language="javascript">  
function changeBox() 
{
  document.getElementById('div1').style.display='none';
  document.getElementById('div2').style.display='';
  document.getElementById('loginpass').focus();
}

function restoreBox() 
{
	if(document.getElementById('loginpass').value=='')
	{
	document.getElementById('div1').style.display='';
	document.getElementById('div2').style.display='none';
	}
}

$(document).ready(function() {
    $(".login_panel").delay(500).fadeIn(500);
    $("#page_middle").delay(500).fadeIn(500);
    $(".login_logo").delay(500).fadeIn(500);
    $(".admin_logo").delay(500).fadeIn(500);
    $(".loading").delay(500).fadeOut(500);

});
$(function() {
    $(".table_drop a").on("click", function(e) {
        e.preventDefault();
    $("#box_cont").fadeIn( 150 );
    $("#box").fadeIn( 150 );
     e.stopPropagation();


$("#box").click(function(e){
    e.stopPropagation();
});

$("#box_cont").click(function(){
    $("#box_cont").fadeOut( 150 );
    $("#box").fadeOut( 150 );
});
        $("#box").load(this.href + " #drop_content");
    });
});

$(".dragd").click(function(){
        $('#d').animate({ marginTop: '-185px'}, 1000);
        $(".dragd").fadeOut(500)
        $(".dragu").fadeIn(500)
        $('#d')
        
       .css('webkit-border-top-right-radius', '25px')
         .css('webkit-border-top-left-radius', '25px')
         .css('-moz-border-radius-topright', '25px')
         .css('-moz-border-radius-topleft', '25px')
          .css('border-top-right-radius', '25px')
           .css('border-top-left-radius', '25px');

        
 
    });
    $(".dragu").click(function(){
        $('#d').animate({ marginTop: '0px'}, 1000);
        $(".dragu").fadeOut(500)
        $(".dragd").fadeIn(500)
        $('#d')
        
         .css('webkit-border-top-right-radius', '0px')
         .css('webkit-border-top-left-radius', '0px')
         .css('-moz-border-radius-topright', '0px')
         .css('-moz-border-radius-topleft', '0px')
          .css('border-top-right-radius', '0px')
           .css('border-top-left-radius', '0px');
    });
     $(".admin_menu_drop").hide();
$(".admin_menu_button").hover(
    function(e){ jQuery(".admin_menu_drop", this).slideDown(100);}, // over
    function(e){ jQuery(".admin_menu_drop", this).slideUp(100); }  // out
);   
 $('.admin_menu_drop:empty').remove();        
$(function() {
    $('#select_all').change(function(){
        var checkboxes = $(this).closest('.show_cats_container').find(':checkbox');
        if($(this).prop('checked')) {
          checkboxes.prop('checked', true);
        } else {
          checkboxes.prop('checked', false);
        }
    });
});  
$(function() {
    $('#select_all_cat').change(function(){
        var checkboxes = $(this).closest('.show_cats_container').find(':checkbox');
        if($(this).prop('checked')) {
          checkboxes.prop('checked', true);
        } else {
          checkboxes.prop('checked', false);
        }
    });
});  
$(function() {
    $('#select_all_pages').change(function(){
        var checkboxes = $(this).closest('.show_pages_container').find(':checkbox');
        if($(this).prop('checked')) {
          checkboxes.prop('checked', true);
        } else {
          checkboxes.prop('checked', false);
        }
    });
});  
$(document).keyup(function(e) {
    if (e.keyCode == 27) {
        $("#selectCatMain").fadeOut(300); 
    }
}); 
$('.table_drop').hide();
$('.page_table_row_user').click(function(){
var $height = 70;
      if ( $(this).height() < $height) {
 $(this).animate({height:'70px'}, 500);
    $('.table_drop', this).slideDown(500);
}
else if ( $(this).height() == $height){
$(this).animate({height:'40px'}, 500);
    $('.table_drop', this).hide(500);
}   
});    
</script>
{/literal}
</body>
</html>
