function amazon_checkout_redirect() {	
	var _rBlock = document.getElementById('amazon-redirect-block');
	//_rBlock.innerHTML = "<iframe id='amazon-redirect-frame' name='amazon-redirect-frame' src='./checkout-2'></iframe>";
	//_rFrame = document.getElementById('amazon-redirect-frame');
	_rBlock.style.display = "block";
	_rBlock.style.width = "100%";
	_rBlock.style.height = "1px";	
	_rBlock.style.border = "1px solid #EFEFEF";
	_rBlock.innerHTML = "";
	//var _rFrameBody = window["amazon-redirect-frame"].document.body;
	//_rFrameBody.innerHTML = '';
    var _data = { action: 'woozone_woo_cart_amazon_redirect' };
    jQuery.post(ajaxurl, _data, function(_r){
		//console.log(_r);
		_rBlock.innerHTML = _r;
		  jQuery( "#amazon-redirect-block" ).animate({height: "110px"}, 3200, ()=>{				
				 setTimeout(function() { document.getElementById("amzRedirect").submit(); }, 3000);
			});    
    });		
};

	





(function( $ ) {
	'use strict';
	
	var tabs_wrapper = $('#thwmscf_wrapper');
	var tabs = $('#thwmscf-tabs');
	var tab_panels = $('#thwmscf-tab-panels');
	var first_step = 0;
	var last_step = 0;
	
	var button_prev = $('#action-prev');
	var button_next = $('#action-next');

	var active_step = 1;

	function initialize_thwmsc(){
		if(tabs_wrapper.length){
			var first_step_tab = tabs.find('li.thwmscf-tab a.first');
			first_step = first_step_tab.data('step');
			last_step = tabs.find('li.thwmscf-tab a.last').data('step');
			
			jump_to_step(first_step, first_step_tab);
		
			tabs.find('li.thwmscf-tab a').click(function(){
				var step_number = $(this).data('step');
				if(step_number < active_step){
					jump_to_step(step_number, $(this));	
				}
			});
			
			button_prev.click(function(){
				var step_number = active_step-1;
				if(step_number >= first_step){
					jump_to_step(step_number, false);
					scroll_to_top();
				}
			});
			
			button_next.click(function(){
				var step_number = active_step+1;
				if(step_number <= last_step){
					jump_to_step(step_number, false);	
					scroll_to_top();
				}
			});
		}
	}
	
	function jump_to_step(step_number, step){
		if(!step){
			step = tabs.find('#step-'+step_number);
		}
		
		tabs.find('li a').removeClass('active');
		var active_tab_panel = tab_panels.find('#thwmscf-tab-panel-'+step_number);
		
		if(!step.hasClass("active")){
			step.addClass("active");
		}
		
		tab_panels.find('div.thwmscf-tab-panel').not('#thwmscf-tab-panel-'+step_number).hide();
		active_tab_panel.show();
		active_step = step_number;
		
		button_prev.prop('disabled', false);
		button_next.prop('disabled', false);
		
		if(active_step == first_step){
			button_prev.prop('disabled', true);
		}
		if(active_step == last_step){
			button_next.prop('disabled', true);
		}
	}
	
	function scroll_to_top(){
		$('html, body').animate({
			scrollTop: tabs_wrapper.offset().top-100
		},800);	
	}
	
	/*----- INIT -----*/
	initialize_thwmsc();

})( jQuery );




/*
jQuery(document).ready(function($){
	
	var col_count = column_count();	
	init_thwmsc_checkout_wizard();

	function init_thwmsc_checkout_wizard(action,stage){ 
		var active;
		var active_step;
		var current_step;

		if (action === undefined) {
		    action = null;
		}
		if (stage === undefined) {
		    stage = null;
		}

		var step = $('#checkout_steps');
		var button = $('.buttons');

		if(action != null){			
			active = step.find('li.active');
			active_step = active.attr("class");

			if(active_step != undefined){
				active_step = active_step.replace('step','');
				active_step = $.trim(active_step.replace('active',''));
			}

			current_step = active_step;

			if(action == "next"){
				if(current_step){ 
					active.addClass('past_step');							
					$('.wmsc_'+active_step).hide();
					step.find("li[data-step="+stage+"]").addClass('active').siblings().removeClass('active');		
					//window.scrollTo(0, 0);	

					$('html, body').animate({
				        scrollTop: $('#checkout_steps').offset().top -100
				    }, 1000);				
				}

			}else if(action == "prev"){ 		
				$('.wmsc_'+active_step).hide();  
				var new_step = step.find("li[data-step="+stage+"]");     
				//var old_stage = stage -1 ;   

				//step.find("li[data-step="+old_stage+"]").addClass('past_step').siblings().removeClass('past_step');	
				
				new_step.removeClass('past_step');			

				new_step.addClass('active').siblings().removeClass('active'); 
				//window.scrollTo(0, 0);
				$('html, body').animate({
			        scrollTop: $('#checkout_steps').offset().top -100
			    }, 1000);
			}		
			
		}			
		
		active = step.find('li.active');
		active_step = active.attr("class");
		var stage = active.data('step');

		var prev_stage = stage;
		var next_stage = stage;

		if(active_step != undefined){
			active_step = active_step.replace('step','');
			active_step = $.trim(active_step.replace('active',''));
		} 

		next_stage++; 
		prev_stage--;  
		
		button.find('.next').data('step',next_stage);
		button.find('.previous').data('step',prev_stage);

		if((stage == 0) && active.hasClass('login')){  			

			button.find('.previous').hide(); 
			// button.find('.previous').prop("disabled", true);
			button.find('.next').val('Continue Without Login');

		}else if((stage == 1)){	

			button.find('.next').val('Next');
			button.find('.previous').hide();			
			//button.find('.previous').prop("disabled", true);			
			if($('.step.login').length == 0) {
		 		button.find('.previous').hide();
		 		// button.find('.previous').prop("disabled", true);
		 	}else{
		 		button.find('.previous').show();
		 		// button.find('.previous').prop("disabled", false);
		 	}

		}else{			
			button.find('.previous').show();
			// button.find('.previous').prop("disabled", false);			
		}

		if(stage == 3){
			button.find('.next').hide();
			// button.find('.next').prop("disabled", true);     			
		}else{
			button.find('.next').show();
			// button.find('.next').prop("disabled", false); 
		}

		$('.wmsc_'+active_step).show();

	}	

	$('.buttons .next').click(function(){
		var action = $(this).data('action');	
		var step = $(this).data('step');

		checkout_display(action,step);				
	});
	
	$('.buttons .previous').click(function(){ 
		var action = $(this).data('action');	
		var step = $(this).data('step');

		checkout_display(action,step);	
	});

	$('#checkout_steps li').click(function(){
		var parent = $(this).parent();
		var active = parent.find('li.active').data('step');
		var step = $(this).data('step');
		var i = step;
		var action = "prev";

		for(i; i<active; i++){
			parent.find("li[data-step="+i+"]").removeClass('past_step');
		}

		if(step < active){	 		
			checkout_display(action,step);	
		}  
	});	

	function column_count(){
		var count = 0;
		$('#checkout_steps li').each(function(){
			count++;		
		});
		return count;
	}

	var column = column_count(); 
	$('#checkout_steps').addClass('column_'+column);
	

});
*/