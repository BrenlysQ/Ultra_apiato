
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});
var windowwidth = $(window).width();
var widthsize = 0;
console.log(windowwidth);
if ((windowwidth > 768) && (windowwidth < 991)) {
	widthsize = 180;
	initcard(widthsize);
}if ((windowwidth > 992) && (windowwidth < 1199)) {
	widthsize = 300;
	initcard(widthsize);
} else if (windowwidth > 1199) {
	widthsize = 350;
	initcard(widthsize);
}

function initcard(widthsize) {
	var card2 = new card({
	    form: 'form',
	    container: '.card-wrapper',
	    width: widthsize,
      formSelectors: {
        numberInput: 'input[name="card_number"]', // optional — default input[name="number"]
        expiryInput: 'input#expiry_date', // optional — default input[name="expiry"]
        cvcInput: 'input#cvc_number', // optional — default input[name="cvc"]
        nameInput: 'input[name="name_holder"]' // optional - defaults input[name="name"]
    },
	    placeholders: {
	        number: '**** **** **** ****',
	        name: 'Arya Stark',
	        expiry: '**/****',
	        cvc: '***'
	    },
	    debug: true
	});
}
