/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
		'icon-glyphicons-82-refresh': '&#xe92b;',
		'icon-glyph_icon_switch': '&#xe92a;',
		'icon-colours': '&#xe929;',
		'icon-bar-graph': '&#xe928;',
		'icon-select-arrows': '&#xe927;',
		'icon-plus': '&#xe926;',
		'icon-check': '&#xe925;',
		'icon-Dash': '&#xe900;',
		'icon-chevron-small-left': '&#xe901;',
		'icon-chevron-small-right': '&#xe902;',
		'icon-palette': '&#xe903;',
		'icon-log-out': '&#xe904;',
		'icon-magnifying-glass': '&#xe905;',
		'icon-price-tag': '&#xe906;',
		'icon-tag': '&#xe907;',
		'icon-menu': '&#xe908;',
		'icon-clipboard': '&#xe909;',
		'icon-message': '&#xe90a;',
		'icon-chat': '&#xe90b;',
		'icon-circle-with-cross': '&#xe90c;',
		'icon-gauge': '&#xe90d;',
		'icon-star': '&#xe90e;',
		'icon-star-outlined': '&#xe90f;',
		'icon-globe': '&#xe910;',
		'icon-info-with-circle': '&#xe911;',
		'icon-info': '&#xe912;',
		'icon-help': '&#xe913;',
		'icon-trash': '&#xe914;',
		'icon-tools': '&#xe915;',
		'icon-database': '&#xe916;',
		'icon-text': '&#xe917;',
		'icon-line-graph': '&#xe918;',
		'icon-cog': '&#xe919;',
		'icon-login': '&#xe91a;',
		'icon-user': '&#xe91b;',
		'icon-help-with-circle': '&#xe91c;',
		'icon-export': '&#xe91d;',
		'icon-v-card': '&#xe91e;',
		'icon-pencil': '&#xe91f;',
		'icon-home': '&#xe920;',
		'icon-list': '&#xe921;',
		'icon-new-message': '&#xe922;',
		'icon-users': '&#xe923;',
		'icon-email': '&#xe924;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
