(function($) {
$(function () {
	$("#contactpage").validate({
		submitHandler: function (e) {
			submitSignupFormNow($("#contactpage"))
		},
		rules: {
			fname: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true,
				phone: true
			}
		},
		errorElement: "span",
		errorPlacement: function (e, t) {
			e.appendTo(t.parent())
		}
	});
	submitSignupFormNow = function (e) {
		var t = e.serialize();
		$.ajax({
			url: OptixContact.ajaxUrl,
			type: "POST",
			data: t + "&action=optix_send_contact&optix_contact_nonce=" + OptixContact.nonce,
			dataType: "json",
			success: function (t) {
				if (t.status === "Success") {
					$("#form_result").html('<span class="form-success alert alert-success d-block">' + t.msg + "</span>");
					$("#contactpage")[0].reset();
				} else {
					$("#form_result").html('<span class="form-error alert alert-danger d-block">' + t.msg + "</span>")
				}
				$("#form_result").show();
			}
		});
		return false
	}
})
})(jQuery);
