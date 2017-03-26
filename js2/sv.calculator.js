
$(document).ready(function(){
    SV_Calculator();
});
function SV_Calculator(option){
    var defaults = {
        slider_summ:$("#slider-summ-zaim"),
        slider_srok:$("#slider-srok-zaim"),

/*        summ_zaim_min:1000,
        summ_zaim_max:15000,
        summ_zaim_def:6000,
*/
        summ_zaim_min:parseInt($('[name="sum-min"]').val()) ,
        summ_zaim_max:parseInt($('[name="sum-max"]').val()) ,
        summ_zaim_def:parseInt($('[name="sum-def"]').val()) ,

        summ_obj:$('.value-summ-zaim'),
        summ_vozvrat_obj:$('.value-summ-vozvrat'),
        srok_obj:$('.value-srok-zaim'),
        date_obj:$('.value-date'),
        limit_obj:$('.limit-val'),

        summ_field:$('[name="VAL_SUMM_ZAIM"]'),
        summ_field2:$('[name="VAL_SUMM_ZAIM_TO"]'),
        srok_field:$('[name="VAL_SROK_ZAIM"]'),
        srok_field2:$('[name="VAL_SROK_ZAIM_TO"]'),

/*        srok_zaim_min:5,
        srok_zaim_max:30,
        srok_zaim_def:11,
*/
        srok_zaim_min:parseInt($('[name="period-min"]').val()),
        srok_zaim_max:parseInt($('[name="period-max"]').val()),
        srok_zaim_def:parseInt($('[name="period-def"]').val())
    };
    var option = $.extend(defaults,option);

    var persent = parseFloat($("#sv-calc-box").data('persent')) || 0;

    option.summ_obj.html(option.summ_zaim_def);
    option.srok_obj.html(option.srok_zaim_def);
    option.limit_obj.html($("#sv-calc-box").data('limit'));
    option.date_obj.html(editDate(new Date(),option.srok_zaim_def));
    option.summ_field.val(option.summ_zaim_def);
    option.summ_field2.val(option.summ_zaim_def);
    option.srok_field.val(option.srok_zaim_def);
    option.srok_field2.val(option.srok_zaim_def);
    var limit = parseInt($("#sv-calc-box").data('limit'))
    Calc(option.summ_zaim_def,option.srok_zaim_def);
    /*option.srok_field.change(function() {
      var value = $(this).val();
        if(value < 5){
          value = 5;
          $(this).val(value);
        }else if(value > 30){
          value = 30;
          $(this).val(value);
        }
        option.slider_srok.slider("value", value);
        Calc(option.summ_obj.html(),value);
     });*/
	 $('.ui-slider-handle').draggable();


     option.summ_field2.on('keyup',function(e) {
         if ((e.which < 48 || e.which > 58) && e.which!=49 && e.which!=8) return false;
     }).on('change paste',function(e) {
         console.log(parseInt($(this).val()));
         if(parseInt($(this).val()) > limit) {
            $('.limit').addClass('active');
         }else{
            $('.limit').removeClass('active');
         }
         option.slider_summ.slider('value',$(this).val());

     });
     option.srok_field2.on('keyup',function(e) {
         if ((e.which < 48 || e.which > 58) && e.which!=49 && e.which!=8) return false;
     }).on('change paste',function(e) {
         option.slider_srok.slider('value',$(this).val());
     });

     $('.slider-section .minus[rel="summ"]').click(function() {
         var value = option.slider_summ.slider( "values", 0 );
         var new_value = value - 100;
         if(new_value < 1000) new_value = 1000;
         option.slider_summ.slider('value',new_value);
         Calc(new_value,option.slider_srok.slider( "values", 0 ));
     });
     $('.slider-section .plus[rel="summ"]').click(function() {
         var value = option.slider_summ.slider( "values", 0 );
         var new_value = value + 100;
         if(new_value > 15000) new_value = 15000;
         if(new_value > limit) {
            $('.limit').addClass('active');
            //return false;
         }else{
            $('.limit').removeClass('active');
         }
         option.slider_summ.slider('value',new_value);
         Calc(new_value,option.slider_srok.slider( "values", 0 ));
     });
     $('.slider-section .minus[rel="srok"]').click(function() {
         var value = option.slider_srok.slider( "values", 0 );
         var new_value = value - 1;
         if(new_value < 5) new_value = 5;
         option.slider_srok.slider('value',new_value);
         Calc(option.slider_summ.slider( "values", 0 ),new_value);
     });
     $('.slider-section .plus[rel="srok"]').click(function() {
         var value = option.slider_srok.slider( "values", 0 );
         var new_value = value + 1;
         if(new_value > 30) new_value = 30;
         option.slider_srok.slider('value',new_value);
         Calc(option.slider_summ.slider( "values", 0 ),new_value);
     });
     function Calc(sum,srok){
	   sum = parseFloat(sum);
	   srok = parseFloat(srok);
	   url ="";
	    url="http://anketa.money/?utm_source="+(sbjs.get.current.src || '')+"&utm_term="+(sbjs.get.current.trm || '')+"&utm_medium="+(sbjs.get.current.mdm || '')+
		"&utm_content="+(sbjs.get.current.cnt || '')+"&utm_campaign="+(sbjs.get.current.cmp || '')+"&creditAmount="+sum+"&creditDays="+srok+"&brand=vkarmane"; 
		console.info (url);       
       
       /* Это раскоменить для переключения */
     
   //    $('form.round+.my1').attr("method","GET");
//	   $('form.round+.my1').attr("action",url);
       
       /* Это раскоменить для переключения */
	   
       option.summ_obj.html(sum);
       option.summ_field.val(sum);
       option.summ_field2.val(sum);

       option.srok_obj.html(srok);
       option.srok_field.val(srok);
       option.srok_field2.val(srok);
       option.date_obj.html(editDate(new Date(),parseInt(srok)));
       //console.log(editDate(new Date(),srok));
       var total = Math.ceil((sum) + ((srok)*(sum)*(persent)/100));
       option.summ_vozvrat_obj.html(total);

       if($(".right-section-cont").length > 0  )
	   {
		   $(".right-section-cont input[name=sum]").val(sum);
		  // $(".right-section-cont input[name=period]").val(srok);
		   $(".right-section-cont input[name=period]").val(srok);
	   }
       if($(".right-section").length > 0  )
	   {
		   $(".right-section input[name=sum]").val(sum);
		  // $(".right-section-cont input[name=period]").val(srok);
		   $(".right-section input[name=period]").val(srok);
	   }
     }
	 console.log(option.summ_zaim_min);
	 console.log(option.summ_zaim_def);
     option.slider_summ.slider({
        range: "max",
        step:500,
        min: option.summ_zaim_min,
        max: option.summ_zaim_max,
        value: option.summ_zaim_def,
        slide: function( event, ui ) {
          if(ui.value > limit){
            $('.limit').addClass('active');
            //return false;
          }else{
            $('.limit').removeClass('active');
          }
          console.log(option.srok_field2.val());
          Calc(ui.value,option.srok_field2.val());
        },
        change: function( event, ui ) {
          if(ui.value > limit){

            //option.slider_summ.slider('value',limit);
            $('.limit').addClass('active');
            //Calc(limit,option.srok_field.val());
            //return false;
          }else{
            $('.limit').removeClass('active');
          }
          console.log('change');
          Calc(ui.value,option.srok_field2.val());
        }
     });
     option.slider_srok.slider({
        range: "max",
        step:1,
        min: option.srok_zaim_min,
        max: option.srok_zaim_max,
        value: option.srok_zaim_def,
        slide: function( event, ui ) {
          Calc(option.summ_obj.html(),ui.value);
        },
        change: function( event, ui ) {
          Calc(option.summ_field2.val(),ui.value);
        }
     });
}
function formatDate(date){
    var monthList = new Array('01', '02', '03', '04', '05', '06','07', '08', '09', '10', '11', '12');
    var day   = date.getDate(),
        month = date.getMonth(),
        year  = date.getFullYear();

    return day + '.' + monthList[month] + '.' + year;
}
function editDate(date,val){
    return formatDate(new Date(date.setDate(date.getDate() + val)));
}







