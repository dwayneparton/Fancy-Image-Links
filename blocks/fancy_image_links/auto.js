ccmValidateBlockForm = function() {
	if ($("#image-fm-value").val() == '' || $("#image-fm-value").val() == 0) { 
		ccm_addError(ccm_t('image-required'));
	}
	if ($('#link_method').val() == 'file_link' && ($("#fileLinkID-fm-value").val() == '' || $("#fileLinkID-fm-value").val() == 0)) { 
		ccm_addError(ccm_t('file-required'));
	}
	return false;
}

$(function() {
	$('.option').hide();
	var selected = $('#link_method').val();
	$('#'+selected).show();
	
	$('#link_method').change(function(){
		$('.option').hide();
		var selected = $('#link_method').val();
		$('#'+selected).show();
	});
});