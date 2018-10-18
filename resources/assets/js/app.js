
require('./bootstrap');

const moment = require('moment')
require('moment/locale/es')

 Vue.use(require('vue-moment'), {
     moment
 })


 Vue.filter('moment', function(value){
 	if(value && value != '0000-00-00')
 		return moment(value).format('D MMMM YYYY');
 	else 
 		return "";
 }); 

 Vue.filter('spaceCase', function(value){
 	return value.replace(/_/g," "); 
 }); 

 Vue.directive('select', {
     twoWay: true,
     bind: function () {
         $(this.el).select2()
         .on("select2:select", function(e) {
             this.set($(this.el).val());
         }.bind(this));
         },
     update: function(nv, ov) {
         $(this.el).trigger("change");
     }
 });

 Vue.component('tickets', require('./components/Tickets.vue'));

 Vue.component('producto-searcher', require('./components/ProductoSearcher.vue'));

 Vue.component('form-ticket-abogados', require('./components/FormTicketAbogados.vue'));

 Vue.component('table-proceso-masivo', require('./components/TableProcesoMasivo.vue'));

const app = new Vue({
    el: 'body',
});


