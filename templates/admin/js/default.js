<script> 
$("#del_label").hide();
$("#del_button").hover(
    function(e){$("#del_label").show();}, 
    function(e){$("#del_label").hide();}  
);
$(document).ready(function() { 
  $('#selectCatMain').hide();

});

$('.closeCats').click(function(){

        $('#selectCatMain').fadeOut(200);

        return false;

    }); 
    $('.openCats').click(function(){

        $('#selectCatMain').fadeIn(200);

        return false;

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
</script>