/* Cloudflare CNAME Setup Main JS */
$(function () {
	$('[data-toggle="tooltip"]').tooltip();
});

/* Add Record */

var content = $('#dns-content').show();
var types = [
	{
		value: 'CAA',
		content: false,
		element: $('#dns-data-caa').hide()
	},
	{
		value: 'SRV',
		content: false,
		element: $('#dns-data-srv').hide()
	},
	{
		value: 'MX',
		content: true,
		element: $('#dns-mx-priority').hide()
	}
];

$('#type').change(function () {
	var type;
	var showContent = true;
	for (var i = 0; i < types.length; i++) {
		type = types[i];
		if (this.value === type.value) {
			type.element.show();
			showContent = type.content;
		} else {
			type.element.hide();
		}
	}

	if (showContent) {
		content.show();
	} else {
		content.hide();
	}
});

/* Implement "data-selected" feature */

var selects = document.getElementsByTagName("select");

if (selects) {
	for (var j = 0; j < selects.length; j++) {
		var selected = selects[j].getAttribute("data-selected");
		if (!selected) {
			continue;
		}
		var options = selects[j].getElementsByTagName("option");
		for (var i = 0; i < options.length; i++) {
			if (options[i].getAttribute("value") === selected) {
				options[i].setAttribute("selected", "selected");
				break;
			}
		}
	}
}
