function ajaxPost(whereto, data, callback_beforeSend, callback_done) {
	(function($) {
		 var promise = $.ajax({
			'type': 'POST',
			'url': whereto,
			'dataType': 'json',
			'data': data,
			'beforeSend': function() {
				if (typeof callback_beforeSend == 'function') callback_beforeSend.call(this);
			},
		}).done(function(rtn){
			if (typeof callback_done == 'function') callback_done.call(this, rtn);
		});

		return promise;
	 })(jQuery);
}
