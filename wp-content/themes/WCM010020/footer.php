<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage TemplateMela
 * @since TemplateMela 1.0
 */
?>
<?php templatemela_content_after(); ?>
</div>
<!-- .main-content-inner -->
</div>
<!-- .main_inner -->
</div>
<!-- #main -->
<?php templatemela_footer_before(); ?>
</div>

<footer id="colophon" class="site-footer" role="contentinfo">
<div class="footer-top">
	<div class="footer-top-inner">
	<?php templatemela_get_widget('footer-block'); ?>
	</div>
</div>
  <div class="footer_inner">
    <?php templatemela_footer_inside(); ?>	
	
    <?php get_sidebar('footer'); ?>
	<!-- .footer-bottom -->
  </div>
    
  <!--. Footer inner -->
</footer>
<div class="footer-bottom">
	<div class="footer-bottom-container">
	<div class="footer-bottom-left">
      <div class="footer-menu-links">
        <?php
					$tm_footer_menu=array(
					'menu' => 'TM Footer Navigation',
					'depth'=> 1,
					'echo' => false,
					'menu_class'      => 'footer-menu', 
					'container'       => '', 
					'container_class' => '', 
					'theme_location' => 'footer-menu'
					);
					echo wp_nav_menu($tm_footer_menu);				    
					?>
      </div>
      <!-- #footer-menu-links -->
	  
      <div class="site-info">  <?php __( 'Copyright', 'templatemela' ); ?> &copy; <?php echo esc_attr(date('Y')); ?> <a href="<?php echo esc_attr(get_option('tmoption_footer_link')); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" target="_blank" rel="home"><?php echo esc_attr(get_option('tmoption_footer_slog'));?>
        </a>
        <?php do_action( 'templatemela_credits' ); ?>
      </div>
	 </div>
	 <div class="footer-bottom-right">
	  <div id="fifth" class="fifth-widget footer-widget">
    	<?php dynamic_sidebar( 'fifth-footer-widget-area' ); ?>
  	  </div>
	 </div>
	  	       
	  </div>
    </div>
<!-- #colophon -->
<?php templatemela_footer_after(); ?>
	
</div>
<!-- #page -->
<?php tm_go_top(); ?>
<?php 
if(trim(get_option('tmoption_google_analytics_id'))!=''):?>
<?php endif; ?>
<?php templatemela_get_widget('before-end-body-widget'); ?>
<?php wp_footer(); ?>
</body></html>

<script>
var pHACK = function(){	
	var loc = (window.location.href).split('/');
	for(var i=0; i<loc.length; i++){
		if(loc[i]=='shop'){
			this['shop-phack']();
		}	
	}
	
	var miniCart = document.querySelectorAll('.woocommerce-mini-cart-item.mini_cart_item');
	if(miniCart.length){this['mini-phack'](miniCart);}
	
}
	
pHACK.prototype = {	
	'shop-phack': function(){
		//console.log('hoover time...');
		var _gal = document.querySelector('figure.woocommerce-product-gallery__wrapper');
		var _iArr = _gal.querySelectorAll('div.woocommerce-product-gallery__image');
		var _cArr = [];
		_iArr.forEach((e,i)=>{
			_cArr.push(e.querySelector('img'));
			e.remove();
		});
		delete _iArr;
		console.log(_cArr);
		
		var pGal = document.createElement('div');
		pGal.classList.add('pGal-block');
		
		var pGalHead =  document.createElement('div');
		pGalHead.classList.add('pGal-main-block');
			
		pGal.appendChild(pGalHead);
		
		var nl;
		var _img;
		
		
		
		function mainClickResponse(e){
			//console.log('main',e);
			var checkSub = e.target.classList.contains('active-image');
			var _i;
			if(checkSub){
				//console.log('subPassedToMain');				
				_i=e.target.querySelector('img').getAttribute('dataset');
				_i=_cArr[_i].dataset.large_image;
			}else{
				_i = e.target.getAttribute('src');				
			}
			var _popup = document.createElement('a');
			_popup.setAttribute('href' , '#');
			_popup.style.display = 'block';
			_popup.style.position = 'absolute';
			_popup.style.zIndex = 100001;
			_popup.style.width = '8px';
			_popup.style.height = '8px';
			_popup.style.left = '50%';
			_popup.style.top = '50%';
			_popup.style.transform = 'translate(-50%, -50%)';
			_popup.style.background = 'white';
			_popup.setAttribute('id', 'showcase-popup');
			
			_popup.innerHTML = '<img src="'+_i+'" style="position:absolute; left:50%; top:50%; transform:translate(-50%, -50%);" />';			
			
			document.body.appendChild(_popup);
			
			
			
			jQuery( "#showcase-popup" ).animate({
				width: "100%",
				height: "100%"
				}, 300, function() {
					jQuery( "#showcase-popup" ).click(function(){
						_popup.remove();
					})
			});
			
			
		}
		
		function subClickResponse(e){
			//console.log('sub:',e);
			var checkActive = e.target.classList.contains('active-image');
			if(checkActive){
				mainClickResponse(e);
				return;
			}
			
			var t = e.target;
			var _i = t.querySelector('img');
			//console.log(_i);			
			var pNode = e.target.parentNode;
			
			pNode.querySelector('.active-image').classList.remove('active-image');
			e.target.classList.add('active-image');
			
			var core = pNode.parentNode;
			var _mi = core.querySelector('.pGal-main-block img');
			
			_mi.src = _cArr[_i.getAttribute('dataset')].dataset.large_image;		
			
		}
		
		function firstImage(){
			nl = document.createElement('a');
			nl.classList.add('pGal-main-image');
			nl.setAttribute('href', '#');
			_img = new Image();
			_img.src = _cArr[0].dataset.large_image;
			nl.appendChild(_img);
			pGalHead.appendChild(nl);
			nl.addEventListener('click', mainClickResponse, false);
		};
		
		
		if(_cArr.length==1){
			firstImage();
		}else if(_cArr.length > 1){
			var pGalSubImages =  document.createElement('div');
				pGalSubImages.classList.add('pGal-sub-images');
				pGal.appendChild(pGalSubImages);
				firstImage();
			for(var i=0; i<_cArr.length; i++){
				nl = document.createElement('a');
				nl.setAttribute('href', '#');
				nl.classList.add('pGal-sub-image');
				_img = new Image();
				_img.src = _cArr[i].src;
				_img.setAttribute('dataset', i);
				nl.appendChild(_img);
				pGalSubImages.appendChild(nl);
				nl.addEventListener('click', subClickResponse, false);
				if(i==0){nl.classList.add('active-image')};
			}			
		}
		
		_gal.appendChild(pGal);
	},
	'mini-phack' : function(items){
		items.forEach((e,i)=>{
			/*var _img = e.querySelector('img');
			var _link = _img.parentNode;
			
			console.log(_img);
			_img.removeAttribute('alt');
			_img.removeAttribute('srcset');
			_img.remove();
			
			var _isrc = _img.src;
			console.log(_isrc);*/
			
			//_img.removeAttribute('dataset')		
			//_link.removeChild(_img);		
			//_link.innerHTML = '<img src="'+_img.src+'" />'+_link.innerHTML;

		});		
	}
}

document.addEventListener('DOMContentLoaded', ()=>{
	var phack = new pHACK();	
}, false);
</script>
